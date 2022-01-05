<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Bridge;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\InterfaceNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;
use ReflectionClass;
use ReflectionMethod;

class BridgeMethodsInterfaceRule extends SprykerAbstractRule implements ClassAware
{
    /**
     * @var string
     */
    protected const RULE = 'All bridge interface methods must have exactly the same or more strict signature as their parent';

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::RULE;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (
            preg_match('([A-Za-z0-9]+Bridge$)', $node->getName()) === 0 ||
            preg_match('#.*\\\\Dependency\\\\.*#', $node->getNamespaceName()) === 0 ||
            !$node instanceof ClassNode
        ) {
            return;
        }

        $classNodeInterfaces = $node->getInterfaces();

        if (!$classNodeInterfaces->count()) {
            $message = sprintf(
                'The bridge `%s` doesn\'t  have any interfaces.',
                $node->getName(),
            );
            $this->addViolation($node, [$message]);

            return;
        }

        $firstInterface = $classNodeInterfaces[0];
        $interfaceNode = new InterfaceNode($firstInterface);

        $this->verifyInterface($node, $interfaceNode);
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     * @param \PHPMD\Node\InterfaceNode $interfaceNode
     *
     * @return void
     */
    protected function verifyInterface(ClassNode $node, InterfaceNode $interfaceNode): void
    {
        $bridgedInterfaceReflection = $this->getBridgedInterfaceReflection($node->getMethods());
        if ($bridgedInterfaceReflection === null) {
            $message = sprintf(
                'The bridge constructor doc block is missing a parent interface. That violates the rule "%s"',
                static::RULE,
            );
            $this->addViolation($interfaceNode, [$message]);

            return;
        }

        $notMatchingMethods = $this->findNotMatchingMethodsForBridgeInterface($interfaceNode, $bridgedInterfaceReflection);

        foreach ($notMatchingMethods as ['method' => $notMatchingMethod, 'error' => $errorMsg]) {
            $message = sprintf(
                'The bridge interface has incorrect method signature for \'%s()\'. %s That violates the rule "%s"',
                $notMatchingMethod->getName(),
                $errorMsg,
                static::RULE,
            );
            $this->addViolation($interfaceNode, [$message]);
        }
    }

    /**
     * @param \PHPMD\Node\InterfaceNode $interfaceNode
     * @param \ReflectionClass $bridgedInterfaceReflection
     *
     * @return array|null
     */
    protected function findNotMatchingMethodsForBridgeInterface(InterfaceNode $interfaceNode, ReflectionClass $bridgedInterfaceReflection): ?array
    {
        $notMatchingMethods = [];

        foreach ($interfaceNode->getMethods() as $interfaceMethod) {
            if (!$bridgedInterfaceReflection->hasMethod($interfaceMethod->getName())) {
                $notMatchingMethods[] = [
                    'method' => $interfaceMethod,
                    'error' => 'Method does not exist in parent.',
                ];

                continue;
            }

            $bridgedInterfaceReflectionMethod = $bridgedInterfaceReflection->getMethod($interfaceMethod->getName());

            $interfaceMethodName = sprintf('%s::%s', $interfaceNode->getFullQualifiedName(), $interfaceMethod->getName());

            $interfaceMethodReflection = new ReflectionMethod($interfaceMethodName);



            $errors = $this->compareBridgeAndParentMethodInterface($interfaceMethodReflection, $bridgedInterfaceReflectionMethod);

            $interfaceMethodReturnType = $this->reflectionReturnTypeToString($interfaceMethodReflection);
            $parentInterfaceReturnType = $this->getReturnTypeFromParentInterface($interfaceMethod, $bridgedInterfaceReflection);

            if (!$interfaceMethodReturnType) {
                $errors[] = 'Missed return type.';
            }

            if ($interfaceMethodReturnType && $parentInterfaceReturnType && $interfaceMethodReturnType !== $parentInterfaceReturnType) {
                $errors[] = 'Invalid return type.';
            }

            if ($errors) {
                $notMatchingMethods[] = [
                    'method' => $interfaceMethod,
                    'error' => trim(implode(' ', $errors)),
                ];
            }
        }

        return $notMatchingMethods;
    }

    /**
     * @param \ReflectionMethod $birdgeInterfaceMethod
     * @param \ReflectionMethod $parentInterfaceMethod
     *
     * @return array
     */
    protected function compareBridgeAndParentMethodInterface(ReflectionMethod $birdgeInterfaceMethod, ReflectionMethod $parentInterfaceMethod): array
    {
        $errors = [];
        $birdgeInterfaceMethodParameters = $birdgeInterfaceMethod->getParameters();
        $parentInterfaceMethodParameters = $parentInterfaceMethod->getParameters();

        if ($this->countRequiredParams($birdgeInterfaceMethodParameters) !== $this->countRequiredParams($parentInterfaceMethodParameters)) {
            $errors[] = sprintf('%s params expexted but got %s.', $this->countRequiredParams($parentInterfaceMethodParameters), $this->countRequiredParams($birdgeInterfaceMethodParameters));
        }

        $countParameters = count($birdgeInterfaceMethodParameters);

        for ($i = 0; $i < $countParameters; $i++) {
            $birdgeInterfaceMethodParameter = $birdgeInterfaceMethodParameters[$i];

            if (empty($parentInterfaceMethodParameters[$i])) {
                $errors[] = sprintf('Parameter %s does not exist in bridged method', $birdgeInterfaceMethodParameter->getName());

                continue;
            }

            $parentInterfaceMethodParameter = $parentInterfaceMethodParameters[$i];

            if ($birdgeInterfaceMethodParameter->getType() && !$parentInterfaceMethodParameter->getType()) {
                continue;
            }

            if (
                (string)$birdgeInterfaceMethodParameter->getType() !== (string)$parentInterfaceMethodParameter->getType() ||
                $birdgeInterfaceMethodParameter->isDefaultValueAvailable() !== $parentInterfaceMethodParameter->isDefaultValueAvailable()
            ) {
                $errors[] = sprintf(
                    'Got `%s` but expected to be `%s`.',
                    trim((string)$birdgeInterfaceMethodParameter->getType() . ' $' . (string)$birdgeInterfaceMethodParameter->getName()),
                    trim((string)$parentInterfaceMethodParameter->getType() . ' $' . (string)$parentInterfaceMethodParameter->getName()),
                );
            }
        }

        return $errors;
    }

    /**
     * @param array<\PHPMD\Node\MethodNode> $classMethods
     *
     * @return \ReflectionClass|null
     */
    protected function getBridgedInterfaceReflection(array $classMethods): ?ReflectionClass
    {
        foreach ($classMethods as $classMethod) {
            if ($classMethod->getName() === '__construct') {
                $constructorComment = $classMethod->getNode()->getComment();

                if (!$constructorComment) {
                    return null;
                }

                $firstParameter = $classMethod->getNode()->getParameters()[0];

                if (!$firstParameter) {
                    return null;
                }

                $pattern = '#@param[\s]+(?<interfaceName>.*)[\s]+' . preg_quote($firstParameter->getName()) . '#is';

                if (preg_match($pattern, $constructorComment, $matches) === 0) {
                    return null;
                }

                return new ReflectionClass($matches['interfaceName']);
            }
        }

        return null;
    }

    /**
     * @param array<\ReflectionParameter> $params
     *
     * @return int
     */
    protected function countRequiredParams(array $params): int
    {
        $countParams = 0;

        foreach ($params as $param) {
            if (!$param->isDefaultValueAvailable()) {
                $countParams++;
            }
        }

        return $countParams;
    }

    /**
     * @param \ReflectionMethod $reflactionMethod
     *
     * @return string|null
     */
    protected function reflectionReturnTypeToString(ReflectionMethod $reflactionMethod): ?string
    {
        $returnType = $reflactionMethod->getReturnType();
        if (!$returnType) {
            return null;
        }

        return ($returnType->allowsNull() ? '?' : '') . (string)$returnType;
    }

    /**
     * @param \PHPMD\Node\MethodNode $interfaceMethod
     * @param \ReflectionClass $bridgedInterfaceReflection
     *
     * @return string|null
     */
    protected function getReturnTypeFromParentInterface(MethodNode $interfaceMethod, ReflectionClass $bridgedInterfaceReflection): ?string
    {
        if (!$bridgedInterfaceReflection->hasMethod($interfaceMethod->getName())) {
            return null;
        }

        if ($bridgedInterfaceReflection->getMethod($interfaceMethod->getName())->hasReturnType()) {
            $returnType = $bridgedInterfaceReflection
                ->getMethod($interfaceMethod->getName())
                ->getReturnType();

            return ($returnType->allowsNull() ? '?' : '') . (string)$returnType;
        }

        return null;
    }
}

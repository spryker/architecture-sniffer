<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Bridge;

use ArchitectureSniffer\Common\ClassNameTrait;
use ArchitectureSniffer\Common\PhpDocTrait;
use ArchitectureSniffer\Common\PhpTypesTrait;
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
    use ClassNameTrait;
    use PhpDocTrait;
    use PhpTypesTrait;

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
            !$node instanceof ClassNode ||
            !$this->isBridgeClass($node)
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
                'The bridge interface has incorrect method signature for `%s()`. %s That violates the rule "%s"',
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

            $interfaceMethodReturnType = $this->getReflectionReturnType($interfaceMethodReflection);
            $parentInterfaceReturnType = $this->getReturnTypeFromParentInterface($interfaceMethod, $bridgedInterfaceReflection);

            if (!$interfaceMethodReturnType && !$this->isReturnTypeInPhp7NotAllowed($interfaceMethod)) {
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
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return bool
     */
    protected function isReturnTypeInPhp7NotAllowed(MethodNode $methodNode): bool
    {
        $returnType = $this->getReturnTypeByPhpDoc($methodNode->getNode()->getComment());

        if ($returnType === null) {
            return false;
        }

        return $this->isTypeInPhp7NotAllowed($returnType);
    }

    /**
     * @param \ReflectionMethod $bridgeInterfaceMethod
     * @param \ReflectionMethod $parentInterfaceMethod
     *
     * @return array
     */
    protected function compareBridgeAndParentMethodInterface(ReflectionMethod $bridgeInterfaceMethod, ReflectionMethod $parentInterfaceMethod): array
    {
        $errors = [];
        $bridgeInterfaceMethodParameters = $bridgeInterfaceMethod->getParameters();
        $parentInterfaceMethodParameters = $parentInterfaceMethod->getParameters();

        if ($this->countRequiredParams($bridgeInterfaceMethodParameters) !== $this->countRequiredParams($parentInterfaceMethodParameters)) {
            $errors[] = sprintf('%s params expected but got %s.', $this->countRequiredParams($parentInterfaceMethodParameters), $this->countRequiredParams($bridgeInterfaceMethodParameters));
        }

        $countParameters = count($bridgeInterfaceMethodParameters);

        for ($i = 0; $i < $countParameters; $i++) {
            $bridgeInterfaceMethodParameter = $bridgeInterfaceMethodParameters[$i];

            if (empty($parentInterfaceMethodParameters[$i])) {
                $errors[] = sprintf('Parameter `%s` does not exist in bridged method', $bridgeInterfaceMethodParameter->getName());

                continue;
            }

            $parentInterfaceMethodParameter = $parentInterfaceMethodParameters[$i];

            if ($bridgeInterfaceMethodParameter->getType() && !$parentInterfaceMethodParameter->getType()) {
                continue;
            }

            if (
                (string)$bridgeInterfaceMethodParameter->getType() !== (string)$parentInterfaceMethodParameter->getType() ||
                $bridgeInterfaceMethodParameter->isDefaultValueAvailable() !== $parentInterfaceMethodParameter->isDefaultValueAvailable()
            ) {
                $errors[] = sprintf(
                    'Got `%s` but expected to be `%s`.',
                    trim($bridgeInterfaceMethodParameter->getType() . ' $' . $bridgeInterfaceMethodParameter->getName()),
                    trim($parentInterfaceMethodParameter->getType() . ' $' . $parentInterfaceMethodParameter->getName()),
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
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return string|null
     */
    protected function getReflectionReturnType(ReflectionMethod $reflectionMethod): ?string
    {
        $returnType = $reflectionMethod->getReturnType();
        if (!$returnType) {
            return null;
        }

        return ($returnType->allowsNull() ? '?' : '') . $returnType;
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

            return ($returnType->allowsNull() ? '?' : '') . $returnType;
        }

        return null;
    }
}

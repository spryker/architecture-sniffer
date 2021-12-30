<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Bridge;

use ArchitectureSniffer\ArchitectureSnifferFactoryAwareTrait;
use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\InterfaceNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;
use ReflectionClass;
use ReflectionMethod;

class BridgeMethodsRule extends SprykerAbstractRule implements ClassAware
{
    use ArchitectureSnifferFactoryAwareTrait;

    protected const RULE = 'All bridge methods must have exactly the same signature as their interface';

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
    public function apply(AbstractNode $node)
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
                $node->getName()
            );
            $this->addViolation($node, [$message]);

            return;
        }

        $firstInterface = $classNodeInterfaces[0];

        $interfaceNode = new InterfaceNode($firstInterface);
        $this->verifyClass($node, $interfaceNode);
        $this->verifyInterface($node, $interfaceNode);
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     * @param \PHPMD\Node\InterfaceNode $interfaceNode
     *
     * @return void
     */
    protected function verifyClass(ClassNode $node, InterfaceNode $interfaceNode): void
    {
        $classMethods = $node->getMethods();
        $interfaceMethods = $interfaceNode->getMethods();

        $notMatchingMethods = $this->findNotMatchingMethodsForBridgeClass($classMethods, $interfaceMethods);

        foreach ($notMatchingMethods as $notMatchingMethod) {
            $message = sprintf(
                'The bridge has incorrect method \'%s\' signature. That violates the rule "%s"',
                $notMatchingMethod->getName(),
                static::RULE
            );

            $this->addViolation($node, [$message]);
        }
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
                'The bridge is missing an interface. That violates the rule "%s"',
                static::RULE
            );
            $this->addViolation($interfaceNode, [$message]);

            return;
        }

        $invalidReturnTypeMethods = $this->findInvalidReturnTypesMethodsForBridgeInterface($interfaceNode, $bridgedInterfaceReflection);

        foreach ($invalidReturnTypeMethods as $invalidReturnTypeMethod) {
            $message = sprintf(
                'The bridge interface has incorrect method \'%s\' signature. Metod has missing or invalid return type. That violates the rule "%s"',
                $invalidReturnTypeMethod->getName(),
                static::RULE
            );
            $this->addViolation($interfaceNode, [$message]);
        }

        $notMatchingMethods = $this->findNotMatchingMethodsForBridgeInterface($interfaceNode, $bridgedInterfaceReflection);

        foreach ($notMatchingMethods as $notMatchingMethod) {
            $message = sprintf(
                'The bridge interface has incorrect method \'%s\' signature. That violates the rule "%s"',
                $notMatchingMethod->getName(),
                static::RULE
            );
            $this->addViolation($interfaceNode, [$message]);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode[] $classMethods
     * @param \PHPMD\Node\MethodNode[] $interfaceMethods
     *
     * @return array|null
     */
    protected function findNotMatchingMethodsForBridgeClass(array $classMethods, array $interfaceMethods): ?array
    {
        $notMatchingMethods = [];

        foreach ($classMethods as $classMethod) {
            if (!$classMethod->isPublic()) {
                continue;
            }

            foreach ($interfaceMethods as $interfaceMethod) {
                if (!$interfaceMethod->isPublic()) {
                    continue;
                }

                if ($classMethod->getName() !== $interfaceMethod->getName()) {
                    continue;
                }

                if ($this->compareTwoMethodForBridgeClass($classMethod, $interfaceMethod)) {
                    continue;
                }

                $notMatchingMethods[] = $classMethod;
            }
        }

        return $notMatchingMethods;
    }

    /**
     * @param \PHPMD\Node\MethodNode $firstMethod
     * @param \PHPMD\Node\MethodNode $secondMethod
     *
     * @return bool
     */
    protected function compareTwoMethodForBridgeClass(MethodNode $firstMethod, MethodNode $secondMethod): bool
    {
        if ($firstMethod->getParameterCount() !== $secondMethod->getParameterCount()) {
            return false;
        }

        $countParameters = $firstMethod->getParameterCount();
        $firstMethodParameters = $firstMethod->getNode()->getParameters();
        $secondMethodParameters = $secondMethod->getNode()->getParameters();

        for ($i = 0; $i < $countParameters; $i++) {
            if ((string)$firstMethodParameters[$i] !== (string)$secondMethodParameters[$i]) {
                return false;
            }
        }

        return true;
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
                $notMatchingMethods[] = $interfaceMethod;

                continue;
            }

            $bridgedInterfaceReflectionMethod = $bridgedInterfaceReflection->getMethod($interfaceMethod->getName());

            $interfaceMethodName = sprintf('%s::%s', $interfaceNode->getFullQualifiedName(), $interfaceMethod->getName());
            $interfaceMethodReflection = new ReflectionMethod($interfaceMethodName);

            if ($this->compareTwoMethodsForBridgeInterface($interfaceMethodReflection, $bridgedInterfaceReflectionMethod)) {
                continue;
            }

            $notMatchingMethods[] = $interfaceMethod;
        }

        return $notMatchingMethods;
    }

    /**
     * @param \PHPMD\Node\InterfaceNode $interfaceNode
     * @param \ReflectionClass $bridgedInterfaceReflection
     *
     * @return array
     */
    protected function findInvalidReturnTypesMethodsForBridgeInterface(InterfaceNode $interfaceNode, ReflectionClass $bridgedInterfaceReflection): array
    {
        $invalidReturnTypeMethods = [];

        foreach ($interfaceNode->getMethods() as $interfaceMethod) {

            $interfaceMethodName = sprintf('%s::%s', $interfaceNode->getFullQualifiedName(), $interfaceMethod->getName());
            $interfaceMethodReflection = new ReflectionMethod($interfaceMethodName);

            $interfaceMethodReturnType = $this->reflactionReturnTypeToString($interfaceMethodReflection);
            $validReturnTypeFromParentInterface = $this->getValidReturnTypeFromParentInterface($interfaceMethod, $bridgedInterfaceReflection);
            $returnTypeFromDocComment = $this->getPhpDocCommentReturnType($interfaceMethod->getComment());

            if ($this->compareReturnTypes($interfaceMethodReturnType, $validReturnTypeFromParentInterface, $returnTypeFromDocComment)) {
                continue;
            }

            $invalidReturnTypeMethods[] = $interfaceMethod;
        }

        return $invalidReturnTypeMethods;
    }

    /**
     * @param \PHPMD\Node\MethodNode $interfaceNode
     * @param \ReflectionClass $bridgedInterfaceReflection
     *
     * @return string|null
     */
    protected function getValidReturnTypeFromParentInterface(MethodNode $interfaceMethod, ReflectionClass $bridgedInterfaceReflection): ?string
    {

        if (!$bridgedInterfaceReflection->hasMethod($interfaceMethod->getName())) {
            return null;
        }

        if ($bridgedInterfaceReflection->getMethod($interfaceMethod->getName())->hasReturnType()) {
            return (string)$bridgedInterfaceReflection
                    ->getMethod($interfaceMethod->getName())
                    ->getReturnType();
        }

        return $this->getPhpDocCommentReturnType($bridgedInterfaceReflection->getMethod($interfaceMethod->getName())->getDocComment());
    }


    /**
     * @param string $interfaceMethodReturnType
     * @param string|null $validReturnTypeFromParentInterface
     * @param string|null $returnTypeFromDocComment
     *
     * @return bool
     */
    protected function compareReturnTypes(?string $interfaceMethodReturnType, ?string $validReturnTypeFromParentInterface, ?string $returnTypeFromDocComment): bool
    {
        if (!$interfaceMethodReturnType) {
            return false;
        }

        if ($validReturnTypeFromParentInterface && $validReturnTypeFromParentInterface !== $interfaceMethodReturnType) {
            return false;
        }

        if ($returnTypeFromDocComment && $returnTypeFromDocComment !== $interfaceMethodReturnType) {
            return false;
        }

        return true;
    }

    /**
     * @param string $comment
     *
     * @return string|null
     */
    protected function getPhpDocCommentReturnType(?string $docComment): ?string
    {
        if (!$docComment) {
            return null;
        }

        $docblock = $this->getFactory()->createPhpDocumetorDocBlock($docComment);
        $returnTags = $docblock->getTagsByName('return');

        if (count($returnTags)) {
            $returnTypeString = ltrim($returnTags[0], '\\');
            $returnTypes =  explode('|', $returnTypeString);
            $isAllowNull = in_array('null', $returnTypes);

            foreach ($returnTypes as $returnType) {
                if($returnType === 'null') {
                    continue;
                }

                if(strpos($returnType, '[]') || strpos($returnType, 'array') === 0 ) {
                    $returnType = 'array';
                }

                return ($isAllowNull ? '?' : ''). ltrim($returnType, '\\');
            }
        }

        return null;
    }


    /**
     * @param \ReflectionMethod $firstMethod
     * @param \ReflectionMethod $secondMethod
     *
     * @return bool
     */
    protected function compareTwoMethodsForBridgeInterface(ReflectionMethod $firstMethod, ReflectionMethod $secondMethod): bool
    {
        $firstMethodParameters = $firstMethod->getParameters();
        $secondMethodParameters = $secondMethod->getParameters();

        if ($this->countRequireParams($firstMethodParameters) !== $this->countRequireParams($secondMethodParameters)) {
            return false;
        }

        $countParameters = count($firstMethodParameters);

        for ($i = 0; $i < $countParameters; $i++) {
            if ((string)$firstMethodParameters[$i] !== (string)$secondMethodParameters[$i]) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \PHPMD\Node\MethodNode[] $classMethods
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

                //todo: Spryker\Zed\Ratepay\Dependency\Facade\RatepayToSalesAggregatorBridge - need to fix
                return new ReflectionClass($matches['interfaceName']);
            }
        }

        return null;
    }

    /**
     * @param \ReflectionParameter[] $params
     * @return int
     */
    protected function countRequireParams(array $params): int
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
    protected function reflactionReturnTypeToString(\ReflectionMethod $reflactionMethod) : ?string
    {
        $returnType  = $reflactionMethod->getReturnType();
        if(!$returnType) {
            return null;
        }

        return ($returnType->allowsNull() ? '?' : '') . (string) $returnType;
    }
}

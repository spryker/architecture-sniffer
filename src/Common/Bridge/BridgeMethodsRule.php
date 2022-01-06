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

class BridgeMethodsRule extends SprykerAbstractRule implements ClassAware
{
    /**
     * @var string
     */
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
                $node->getName(),
            );
            $this->addViolation($node, [$message]);

            return;
        }

        $firstInterface = $classNodeInterfaces[0];

        $interfaceNode = new InterfaceNode($firstInterface);
        $this->verifyClass($node, $interfaceNode);
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
                'The bridge has incorrect method signature for `%s()`. That violates the rule "%s"',
                $notMatchingMethod->getName(),
                static::RULE,
            );

            $this->addViolation($node, [$message]);
        }
    }

    /**
     * @param array<\PHPMD\Node\MethodNode> $classMethods
     * @param array<\PHPMD\Node\MethodNode> $interfaceMethods
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
}

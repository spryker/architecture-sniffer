<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\DependencyProvider;

use ArchitectureSniffer\Common\DeprecationTrait;
use PHPMD\AbstractRule;
use PHPMD\Node\AbstractNode;
use PHPMD\Node\MethodNode;

abstract class AbstractDependencyProviderRule extends AbstractRule
{
    use DeprecationTrait;

    public const RULE = 'DependencyProvider should only contain additional add*() or get*() methods.';

    /**
     * @param \PHPMD\Node\AbstractNode $node
     * @param string $application
     *
     * @return bool
     */
    protected function isDependencyProvider(AbstractNode $node, $application)
    {
        $className = $node->getFullQualifiedName();
        if ($node instanceof MethodNode) {
            $parent = $node->getNode()->getParent();
            $className = $parent->getNamespaceName() . '\\' . $parent->getName();
        }

        if (preg_match('/\\\\' . $application . '\\\\.*\\\\\w+DependencyProvider$/', $className)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param array $allowedProvideMethodNames
     *
     * @return void
     */
    protected function applyRule(MethodNode $method, array $allowedProvideMethodNames)
    {
        if ($this->isMethodDeprecated($method)) {
            return;
        }

        if (in_array($method->getName(), $allowedProvideMethodNames)) {
            return;
        }

        if (preg_match('/^(add|get).+/', $method->getName()) !== 0) {
            return;
        }

        $this->addViolationMessage($method);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function hasPropelQueryAllocationExpression(MethodNode $method): bool
    {
        foreach ($method->findChildrenOfType('ClassOrInterfaceReference') as $referenceNode) {
            $isQueryReference = (bool)strpos($referenceNode->getName(), 'Query');

            if (!$isQueryReference) {
                continue;
            }

            $methodPostfixChild = $method->getFirstChildOfType('MethodPostfix');

            if ($methodPostfixChild !== null && $methodPostfixChild->getName() === 'create') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return void
     */
    protected function addViolationMessage(AbstractNode $node): void
    {
        $message = sprintf(
            'The DependencyProvider method %s() violates rule "%s"',
            $node->getName(),
            static::RULE
        );

        $this->addViolation($node, [$message]);
    }
}

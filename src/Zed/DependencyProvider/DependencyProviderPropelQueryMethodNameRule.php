<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Zed\DependencyProvider;

use ArchitectureSniffer\Common\DependencyProvider\AbstractDependencyProviderRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class DependencyProviderPropelQueryMethodNameRule extends AbstractDependencyProviderRule implements MethodAware
{
    public const RULE = 'Add propel query methods must be named like add*PropelQuery() in dependency provider.';
    protected const RULE_REGULAR_EXPRESSION_PATTERN = '/^add([a-zA-Z]+)PropelQuery$/';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (!$this->isDependencyProvider($node, 'Zed')) {
            return;
        }

        if (!$this->hasPropelQueryAllocationExpression($node)) {
            return;
        }

        $this->applyPropelQueryMethodNameRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyPropelQueryMethodNameRule(MethodNode $method): void
    {
        $methodName = $method->getName();

        if (0 !== preg_match(static::RULE_REGULAR_EXPRESSION_PATTERN, $methodName)) {
            return;
        }

        $this->addViolationMessage($method);
    }
}

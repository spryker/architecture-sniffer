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

class DependencyProviderPropelQueryConstantNameRule extends AbstractDependencyProviderRule implements MethodAware
{
    public const RULE = 'Propel query constants must be named like PROPEL_QUERY_* in dependency provider.';
    protected const RULE_REGULAR_EXPRESSION_PATTERN = '/^PROPEL_QUERY_.+/';

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

        $this->applyPropelQueryConstNameRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyPropelQueryConstNameRule(MethodNode $method): void
    {
        $constant = $method->getFirstChildOfType('ConstantPostfix');

        if (0 !== preg_match(static::RULE_REGULAR_EXPRESSION_PATTERN, $constant->getName())) {
            return;
        }

        $this->addViolationMessage($method);
    }
}

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Zed\DependencyProvider;

use ArchitectureSniffer\Common\DependencyProvider\AbstractDependencyProviderRule;
use ArchitectureSniffer\SprykerPropelQueryRulePatterns;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class DependencyProviderPropelQueryConstantNameRule extends AbstractDependencyProviderRule implements MethodAware
{
    public const RULE = 'Propel query constants must be named like PROPEL_QUERY_* in dependency provider.';

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

        if (preg_match(SprykerPropelQueryRulePatterns::PATTERN_PROPEL_QUERY_CONSTANT_NAME, $constant->getName()) !== 0) {
            return;
        }

        $this->addViolationMessage($method);
    }
}

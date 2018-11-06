<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Factory;

use ArchitectureSniffer\SprykerPropelQueryRulePatterns;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FactoryPropelQueryMethodNameRule extends AbstractFactoryRule implements MethodAware
{
    public const RULE = 'Getter propel query methods must be named like get*PropelQuery() in factory.';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (!$this->isFactory($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $node
     *
     * @return void
     */
    protected function applyRule(MethodNode $node): void
    {
        $constant = $node->getFirstChildOfType('ConstantPostfix');

        if ($constant === null) {
            return;
        }

        if (preg_match(SprykerPropelQueryRulePatterns::PATTERN_PROPEL_QUERY_CONSTANT_NAME, $constant->getName()) === 0) {
            return;
        }

        $methodName = $node->getName();

        if (preg_match(SprykerPropelQueryRulePatterns::PATTERN_PROPEL_QUERY_FACTORY_METHOD_NAME, $methodName) !== 0) {
            return;
        }

        $class = $node->getParentName();
        $fullClassName = $node->getFullQualifiedName();

        $message = sprintf(
            '%s (%s) returns a concrete class which violates the rule "%s"',
            "{$class}::{$methodName}()",
            $fullClassName,
            static::RULE
        );

        $this->addViolation($node, [$message]);
    }
}

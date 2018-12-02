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
    public const RULE = 'Get propel query methods must be named like get*PropelQuery() in factory.';
    protected const PATTERN_PROPEL_QUERY_CONSTANT_NAME = '/^PROPEL_QUERY_.+/';
    protected const PATTERN_PROPEL_QUERY_FACTORY_METHOD_NAME = '/^get([a-zA-Z]+)PropelQuery$/';

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

        if (preg_match(static::PATTERN_PROPEL_QUERY_CONSTANT_NAME, $constant->getName()) === 0) {
            return;
        }

        $methodName = $node->getName();

        if (preg_match(static::PATTERN_PROPEL_QUERY_FACTORY_METHOD_NAME, $methodName) !== 0) {
            return;
        }

        $class = $node->getParentName();

        $message = sprintf(
            '%s violates rule "%s"',
            "{$class}::{$methodName}()",
            static::RULE
        );

        $this->addViolation($node, [$message]);
    }
}

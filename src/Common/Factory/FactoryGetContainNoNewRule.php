<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FactoryGetContainNoNewRule extends AbstractFactoryRule implements MethodAware
{
    public const RULE = 'A `get*()` method in factories must not contain a `new` keyword.';

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
        if (!$this->isFactory($node)) {
            return;
        }

        if ($this->isMethodDeprecated($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
    {
        if (substr($method->getName(), 0, 3) !== 'get') {
            return;
        }

        $count = count($method->findChildrenOfType('AllocationExpression'));
        if ($count === 0) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The factory method %s contains %d new statements which violates rule "' . static::RULE . '"',
                    $method->getFullQualifiedName(),
                    $count
                ),
            ]
        );
    }
}

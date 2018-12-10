<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Factory;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FactoryOnlyPublicMethodsRule extends SprykerAbstractRule implements MethodAware
{
    public const RULE = 'All the factory methods should be public by default';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        $className = $node->getNode()->getParent()->getNamespacedName();

        if (preg_match('/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+(Business|Service|Communication|Persistence)Factory$/', $className) === 0) {
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
        if ($node->isPublic()) {
            return;
        }

        $method = $node->getName();

        $message = sprintf(
            'The factory method \'%s()\' is not public which violates the rule "%s"',
            $method,
            static::RULE
        );

        $this->addViolation($node, [$message]);
    }
}

<?php

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FactoryOnlyPublicMethodsRule extends AbstractFactoryRule implements MethodAware
{
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

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $node
     *
     * @return void
     */
    protected function applyRule(MethodNode $node)
    {
        if (!$node->isPublic()) {
            $class = $node->getParentName();
            $method = $node->getName();

            $message = sprintf(
                '%s should be public',
                "{$class}::{$method}()"
            );

            $this->addViolation($node, [$message]);
        }
    }
}

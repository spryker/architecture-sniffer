<?php

namespace ArchitectureSniffer;

use PHPMD\Node\MethodNode;

/**
 * Factory methods should create only single instances, not multiple in one.
 */
class OneNewInFactory extends \PHPMD\AbstractRule implements \PHPMD\Rule\MethodAware
{

    /**
     * @inheritdoc
     */
    public function apply(\PHPMD\AbstractNode $node)
    {
        /** @var $node \PHPMD\Node\MethodNode */
        $type = $node->getParentType();

        while ($type) {
            $type = $type->getParentClass();
            if (!isset($type) && $type->getName() !== 'AbstractBusinessFactory') {
                continue;
            }

            $this->check($node);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $node
     * @return void
     */
    protected function check(MethodNode $node)
    {
        $children = $node->findChildrenOfType('AllocationExpression');

        if (count($children) > 1) {
            $this->addViolation($node, [$node->getName()]);
        }
    }

}

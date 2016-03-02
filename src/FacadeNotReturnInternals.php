<?php

namespace ArchitectureSniffer;

use PHPMD\Node\MethodNode;

/**
 * Facade methods should not leak high level objects.
 * Only Transfer objects (DTOs) or primitive types are allowed.
 */
class FacadeNotReturnInternals extends \PHPMD\AbstractRule
    implements \PHPMD\Rule\MethodAware
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
            if (isset($type) && $type->getName() === 'AbstractFacade') {
                $this->check($node);
            }
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $node
     * @return void
     */
    protected function check(MethodNode $node)
    {
        $type = $node->getReturnClass();
        if (!empty($type)) {
            /** @var $type PDepend\Source\AST\ASTClass */
            $parentType = $type->getParentClass();
            while ($parentType) {
                $parentType = $type->getParentClass();

                if (isset($parentType) && $parentType->getName() === 'AbstractTransfer') {
                    return;
                }

            }
             $this->addViolation($node, [$type->getName()]);
            return;
        }

    }
}

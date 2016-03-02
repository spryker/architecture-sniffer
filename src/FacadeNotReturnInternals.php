<?php

namespace ArchitectureSniffer;

use PHPMD\Node\MethodNode;

/**
 * Facade methods should not leak high level objects.
 * Only Transfer objects (DTOs) or primitive types are allowed.
 */
class FacadeNotReturnInternals extends \PHPMD\AbstractRule implements \PHPMD\Rule\MethodAware
{

    /**
     * @inheritdoc
     */
    public function apply(\PHPMD\AbstractNode $node)
    {
        /** @var \PHPMD\Node\MethodNode $node */
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
        if (empty($type)) {
            return;
        }

        /** @var \PDepend\Source\AST\ASTClass $type */
        $parentType = $type->getParentClass();
        while ($parentType) {
            $parentType = $type->getParentClass();

            if (isset($parentType) && $parentType->getName() === 'AbstractTransfer') {
                return;
            }

        }
        $this->addViolation($node, [$type->getName()]);
    }

}

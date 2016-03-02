<?php

namespace ArchitectureSniffer;

/**
 * Facade methods should not contain properties or internal logic.
 */
class FacadeMustNotHaveProperties extends \PHPMD\AbstractRule
    implements \PHPMD\Rule\ClassAware
{

    /**
     * @inheritdoc
     */
    public function apply(\PHPMD\AbstractNode $node)
    {
        /** @var $node \PHPMD\Node\ClassNode */
        $type = $node;
        do {
            $type = $type->getParentClass();
            if (isset($type) && $type->getName() === 'AbstractFacade') {
                $this->check($node);
            }
        } while ($type);
    }

    protected function check(\PHPMD\Node\ClassNode $node)
    {
        $probs = $node->getProperties();

        if (count($probs)) {
            $this->addViolation($node);
        }
    }

}

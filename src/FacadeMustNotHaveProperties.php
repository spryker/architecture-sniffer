<?php

namespace ArchitectureSniffer;

/**
 * Facade methods should not contain properties or internal logic.
 */
class FacadeMustNotHaveProperties extends \PHPMD\AbstractRule implements \PHPMD\Rule\ClassAware
{

    /**
     * @inheritdoc
     */
    public function apply(\PHPMD\AbstractNode $node)
    {
        /** @var \PHPMD\Node\ClassNode $node */
        $type = $node;
        do {
            $type = $type->getParentClass();
            if (isset($type) && $type->getName() === 'AbstractFacade') {
                $this->check($node);
            }
        } while ($type);
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    protected function check(\PHPMD\Node\ClassNode $node)
    {
        $probs = $node->getProperties();

        if (!count($probs)) {
            return;
        }

        $this->addViolation($node);
    }

}

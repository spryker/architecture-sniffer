<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;

/**
 * Facade methods should not contain properties or internal logic.
 */
class FacadeMustNotHaveProperties extends \PHPMD\AbstractRule implements \PHPMD\Rule\ClassAware
{

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
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
    protected function check(ClassNode $node)
    {
        $properties = $node->getProperties();

        if (!count($properties)) {
            return;
        }

        $this->addViolation($node);
    }

}

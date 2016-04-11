<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractRule;
use PHPMD\Node\AbstractNode;
use PHPMD\Node\MethodNode;

abstract class AbstractFacadeRule extends AbstractRule
{

    /**
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return bool
     */
    protected function isFacade(AbstractNode $node)
    {
        if ($node instanceof MethodNode) {
            $parent = $node->getNode()->getParent();
            $className = $parent->getNamespaceName() . '\\' . $parent->getName();
        } else {
            $className = $node->getFullQualifiedName();
        }


        if (preg_match('/\\\\Zed\\\\.*\\\\Business\\\\.*Facade$/', $className)) {
            return true;
        }

        return false;
    }

}

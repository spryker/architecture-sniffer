<?php

namespace ArchitectureSniffer\Zed\Communication\Factory;

use PHPMD\AbstractRule;
use PHPMD\Node\AbstractNode;
use PHPMD\Node\MethodNode;

abstract class AbstractFactoryRule extends AbstractRule
{

    /**
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return bool
     */
    protected function isFactory(AbstractNode $node)
    {
        if ($node instanceof MethodNode) {
            $parent = $node->getNode()->getParent();
            $className = $parent->getNamespaceName() . '\\' . $parent->getName();
        } else {
            $className = $node->getFullQualifiedName();
        }

        if (preg_match('/\\\\Zed\\\\.*\\\\Business\\\\.*Factory$/', $className)) {
            return true;
        }

        return false;
    }

}

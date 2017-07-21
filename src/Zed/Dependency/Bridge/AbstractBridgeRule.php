<?php

namespace ArchitectureSniffer\Zed\Dependency\Bridge;

use PHPMD\AbstractRule;
use PHPMD\Node\AbstractNode;
use PHPMD\Node\MethodNode;

abstract class AbstractBridgeRule extends AbstractRule
{

    /**
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return bool
     */
    protected function isBridge(AbstractNode $node)
    {
        if ($node instanceof MethodNode) {
            $parent = $node->getNode()->getParent();
            $className = $parent->getNamespaceName() . '\\' . $parent->getName();
        } else {
            $className = $node->getFullQualifiedName();
        }

        if (preg_match('/\\\\Dependency\\\\.*\\\\.*Bridge$/', $className)) {
            return true;
        }

        return false;
    }

}

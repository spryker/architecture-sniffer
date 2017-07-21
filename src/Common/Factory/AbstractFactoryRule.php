<?php

namespace ArchitectureSniffer\Common\Factory;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\Node\AbstractNode;
use PHPMD\Node\MethodNode;

abstract class AbstractFactoryRule extends SprykerAbstractRule
{

    const ZED_FACTORY = '/\\\\*\\\\.*\\\\.*\\\\.*Factory$/';

    /**
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return bool
     */
    protected function isFactory(AbstractNode $node)
    {
        if ($node instanceof MethodNode) {
            $node = $this->getNodeFromMethodNode($node);

            $parent = $node->getParent();
            $className = $parent->getNamespaceName() . '\\' . $parent->getName();
        } else {
            $className = $node->getFullQualifiedName();
        }

        // Factories in persistence layer have other rules
        if (strpos($className, '\\Persistence\\') !== false) {
            return false;
        }

        if (preg_match(self::ZED_FACTORY, $className)) {
            return true;
        }

        return false;
    }

}
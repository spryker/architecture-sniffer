<?php

namespace ArchitectureSniffer\Zed\Persistence\Repository;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\Node\AbstractNode;
use PHPMD\Node\MethodNode;

abstract class AbstractRepositoryRule extends SprykerAbstractRule
{
    public const PATTERN_FACTORY = '/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+Repository$/';

    /**
     *
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return bool
     */
    protected function isRepository(MethodNode $methodNode): bool
    {
        if ($methodNode instanceof MethodNode) {
            $node = $this->getNodeFromMethodNode($methodNode);

            $parent = $node->getParent();
            $className = $parent->getNamespaceName() . '\\' . $parent->getName();
        } else {
            $className = $methodNode->getFullQualifiedName();
        }

        if (preg_match(self::PATTERN_FACTORY, $className)) {
            return true;
        }

        return false;
    }
}

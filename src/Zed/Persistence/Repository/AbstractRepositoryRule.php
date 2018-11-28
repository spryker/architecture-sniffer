<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Zed\Persistence\Repository;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\Node\MethodNode;

abstract class AbstractRepositoryRule extends SprykerAbstractRule
{
    public const PATTERN_REPOSITORY = '/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+Repository$/';

    /**
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

        if (preg_match(self::PATTERN_REPOSITORY, $className)) {
            return true;
        }

        return false;
    }
}

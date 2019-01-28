<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Zed\Persistence\Repository;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\Node\ClassNode;

abstract class AbstractRepositoryRule extends SprykerAbstractRule
{
    public const PATTERN_REPOSITORY = '/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+Repository$/';

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return bool
     */
    protected function isRepository(ClassNode $classNode): bool
    {
        if ($classNode instanceof ClassNode) {
            $className = $classNode->getNamespaceName() . '\\' . $classNode->getName();
        } else {
            $className = $classNode->getFullQualifiedName();
        }

        if (preg_match(self::PATTERN_REPOSITORY, $className)) {
            return true;
        }

        return false;
    }
}

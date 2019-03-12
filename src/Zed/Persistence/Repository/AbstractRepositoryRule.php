<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Persistence\Repository;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\Node\ClassNode;

/**
 * @deprecated This class has been replaced by \ArchitectureSniffer\Zed\Persistence\AbstractPersistenceRule
 */
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

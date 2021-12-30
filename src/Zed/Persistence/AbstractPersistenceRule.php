<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Persistence;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\Node\ClassNode;

abstract class AbstractPersistenceRule extends SprykerAbstractRule
{
    /**
     * @var string
     */
    public const PATTERN_REPOSITORY = '/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+Repository$/';

    /**
     * @var string
     */
    public const PATTERN_ENTITY_MANAGER = '/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+EntityManager$/';

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return bool
     */
    protected function isRepository(ClassNode $classNode): bool
    {
        $className = $this->getClassName($classNode);

        if (preg_match(self::PATTERN_REPOSITORY, $className)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return bool
     */
    protected function isEntityManager(ClassNode $classNode): bool
    {
        $className = $this->getClassName($classNode);

        if (preg_match(self::PATTERN_ENTITY_MANAGER, $className)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return string
     */
    protected function getClassName(ClassNode $classNode): string
    {
        if ($classNode instanceof ClassNode) {
            return $classNode->getNamespaceName() . '\\' . $classNode->getName();
        }

        return $classNode->getFullQualifiedName();
    }
}

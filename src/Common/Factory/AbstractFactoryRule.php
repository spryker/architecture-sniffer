<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Factory;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\Node\AbstractNode;
use PHPMD\Node\MethodNode;

abstract class AbstractFactoryRule extends SprykerAbstractRule
{
    public const PATTERN_FACTORY = '/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+(Business|Service|Communication|Persistence)Factory$/';

    /**
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return bool
     */
    protected function isFactory(AbstractNode $node): bool
    {
        $className = $this->getClassName($node);

        if (preg_match(static::PATTERN_FACTORY, $className) === 0) {
            return false;
        }

        return true;
    }

    /**
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return string
     */
    protected function getClassName(AbstractNode $node): string
    {
        if ($node instanceof MethodNode) {
            $node = $this->getNodeFromMethodNode($node);
            $parent = $node->getParent();

            return $parent->getNamespacedName();
        }

        return $node->getFullQualifiedName();
    }

    /**
     * @param \PHPMD\Node\MethodNode $node
     *
     * @return bool
     */
    protected function isMethodDeprecated(MethodNode $node): bool
    {
        if (preg_match('#\@deprecated#', $node->getNode()->getDocComment()) !== 0) {
            return true;
        }

        return false;
    }
}

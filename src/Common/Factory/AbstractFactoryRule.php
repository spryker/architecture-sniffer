<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Factory;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\Node\AbstractNode;
use PHPMD\Node\MethodNode;

abstract class AbstractFactoryRule extends SprykerAbstractRule
{
    public const PATTERN_FACTORY = '/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+(Business|Service|Communication|Persistence)Factory$/';
    public const PATTERN_FACTORY_EXCEPT_PERSISTENCE = '/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+(Business|Service|Communication)Factory$/';

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
     * @return bool
     */
    protected function isFactoryExceptPersistence(AbstractNode $node): bool
    {
        $className = $this->getClassName($node);

        if (preg_match(static::PATTERN_FACTORY_EXCEPT_PERSISTENCE, $className) === 0) {
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
}

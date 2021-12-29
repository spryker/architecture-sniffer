<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Plugin;

use ArchitectureSniffer\SprykerAbstractRule;
use PDepend\Source\AST\ASTClass;
use PHPMD\AbstractNode;
use PHPMD\Rule\ClassAware;

abstract class AbstractPluginRule extends SprykerAbstractRule
{
    /**
     * @var string
     */
    protected const EXTENDS_CLASS_NAME = 'AbstractPlugin';

    /**
     * @var string
     */
    protected const PLUGIN_DIRECTORY_PATTERN = '#(Client|Yves|Service)\\\\.+\\\\Plugin\\\\(?!ServiceProvider|Provider)$|Communication\\\\Plugin\\\\[a-zA-Z0-9]+$#';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isPlugin(AbstractNode $node): bool
    {
        if ($this->isServiceProvider($node)) {
            return false;
        }

        if ($this->extendsAbstractPlugin($node)) {
            return true;
        }

        if ($this->isInPluginDirectory($node)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isInPluginDirectory(AbstractNode $node): bool
    {
        return (preg_match(static::PLUGIN_DIRECTORY_PATTERN, $node->getFullQualifiedName()));
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isServiceProvider(AbstractNode $node): bool
    {
        if (!($this instanceof ClassAware)) {
            return false;
        }

        $interfaceReferences = $this->getInterfaceReferences($node);

        if (!$interfaceReferences) {
            return false;
        }

        foreach ($interfaceReferences as $interfaceReference) {
            if ($interfaceReference->getType()->getName() === 'ServiceProviderInterface' || $interfaceReference->getType()->getName() === 'ControllerProviderInterface') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return array<\PDepend\Source\AST\ASTClassOrInterfaceReference>
     */
    protected function getInterfaceReferences(AbstractNode $node): array
    {
        return $this->getAstClass($node)->getInterfaceReferences();
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function extendsAbstractPlugin(AbstractNode $node): bool
    {
        if (!($this instanceof ClassAware)) {
            return false;
        }

        $parentClass = $this->getAstClass($node)->getParentClass();

        if (!($parentClass instanceof ASTClass)) {
            return false;
        }

        if ($parentClass->getName() === static::EXTENDS_CLASS_NAME) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \PDepend\Source\AST\ASTClass
     */
    protected function getAstClass(AbstractNode $node): ASTClass
    {
        return $node->getNode();
    }
}

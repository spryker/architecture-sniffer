<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Persistence;

use ArchitectureSniffer\Common\DeprecationTrait;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

class SpyEntityUsageRule extends AbstractPersistenceRule implements ClassAware
{
    use DeprecationTrait;

    /**
     * @var string
     */
    public const RULE = 'Entity can be initialized in Repository or EntityManager only.';

    /**
     * @var string
     */
    protected const PATTERN_NAMESPACE_APPLICATION_ZED = '/^[a-zA-Z]+\\\\Zed\\\\/';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (!$this->isZedNamespace($node)) {
            return;
        }

        if ($this->isExcludedModule($node)) {
            return;
        }

        if ($this->isClassDeprecated($node)) {
            return;
        }

        if ($this->isRepository($node)) {
            return;
        }

        if ($this->isEntityManager($node)) {
            return;
        }

        $allocatedClasses = $node->findChildrenOfType('AllocationExpression');

        if (count($allocatedClasses) === 0) {
            return;
        }

        foreach ($allocatedClasses as $allocatedClass) {
            $this->applyRule($allocatedClass, $node);
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return bool
     */
    protected function isZedNamespace(ClassNode $classNode): bool
    {
        $classNamespace = $classNode->getNamespaceName();

        if (!preg_match(static::PATTERN_NAMESPACE_APPLICATION_ZED, $classNamespace)) {
            return false;
        }

        return true;
    }

    /**
     * @param \PHPMD\AbstractNode $allocatedClass
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return void
     */
    protected function applyRule(AbstractNode $allocatedClass, ClassNode $classNode): void
    {
        $entityNamePattern = '/^Spy/';
        $queryNamePattern = '/Query$/';

        $classReference = $allocatedClass->getFirstChildOfType('ClassReference');

        if ($classReference === null) {
            return;
        }

        $className = $classReference->getName();
        $className = explode('\\', $className);
        $className = array_pop($className);

        if (
            !preg_match($entityNamePattern, $className)
            || preg_match($queryNamePattern, $className)
        ) {
            return;
        }

        $message = sprintf(
            'Entity `%s` initialized in `%s`. The class violates rule "%s".',
            $className,
            $classNode->getFullQualifiedName(),
            static::RULE,
        );

        $this->addViolation($classNode, [$message]);
    }

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return bool
     */
    protected function isExcludedModule(ClassNode $classNode): bool
    {
        $moduleName = $this->getModuleName($classNode->getNamespaceName());

        foreach ($this->getExcludedModuleNamePatterns() as $excludedModuleNamePattern) {
            if (preg_match($excludedModuleNamePattern, $moduleName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string>
     */
    protected function getExcludedModuleNamePatterns(): array
    {
        return [
            '/DataImport$/',
            '/DataImportExtension$/',
            '/Storage$/',
            '/StorageExtension$/',
            '/Search$/',
            '/SearchConnector$/',
            '/SearchExtension$/',
        ];
    }
}

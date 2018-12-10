<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Zed\Persistence\Repository;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

class RepositoryCanUseSpyEntityRule extends AbstractRule implements ClassAware
{
    /**
     * TODO: Changes after CrossModule Propel Query usage rule release
     * - AbstractRule will be changed on AbstractRepositoryRule.
     * - isRepository() method will be removed.
     */

    public const RULE = 'Entity can be initialized in Repository only.';

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

        if ($this->isRepository($node)) {
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

        $className = $allocatedClass->getFirstChildOfType('ClassReference')->getName();
        $className = explode('\\', $className);
        $className = array_pop($className);

        if (!preg_match($entityNamePattern, $className)
            || preg_match($queryNamePattern, $className)
        ) {
            return;
        }

        $message = sprintf(
            'Entity `%s` initialized in `%s`. The class violates rule `%s`.',
            $className,
            $classNode->getFullQualifiedName(),
            static::RULE
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
        $moduleName = $this->getModuleName($classNode);

        foreach ($this->getExcludedModuleNamePatterns() as $excludedModuleNamePattern) {
            if (preg_match($excludedModuleNamePattern, $moduleName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
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

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return bool
     */
    protected function isRepository(ClassNode $classNode): bool
    {
        $repositoryPattern = '/\\\\*\\\\.+\\\\.+\\\\[A-Za-z0-9]+Repository$/';

        if ($classNode instanceof ClassNode) {
            $className = $classNode->getNamespaceName() . '\\' . $classNode->getName();
        } else {
            $className = $classNode->getFullQualifiedName();
        }

        if (preg_match($repositoryPattern, $className)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return string
     */
    protected function getModuleName(ClassNode $classNode): string
    {
        $namespace = $classNode->getNamespaceName();
        $namespace = preg_replace(static::PATTERN_NAMESPACE_APPLICATION_ZED, '', $namespace);
        $namespace = explode('\\', $namespace);

        return array_shift($namespace);
    }
}

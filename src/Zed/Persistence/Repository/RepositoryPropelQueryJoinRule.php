<?php /** @noinspection ALL */

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Persistence\Repository;

use ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer;
use ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer;
use ArchitectureSniffer\PropelQuery\PropelQueryFacade;
use ArchitectureSniffer\PropelQuery\PropelQueryFacadeInterface;
use ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer;
use ArchitectureSniffer\Zed\Persistence\AbstractPersistenceRule;
use InvalidArgumentException;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

class RepositoryPropelQueryJoinRule extends AbstractPersistenceRule implements ClassAware
{
    /**
     * @var string
     */
    public const RULE = 'All dependent modules must be declared in the DocBlock.';

    /**
     * @var \ArchitectureSniffer\PropelQuery\PropelQueryFacadeInterface
     */
    protected $facade;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (!$this->isRepository($node)) {
            return;
        }

        $classNodeTransfer = $this->getClassNodeTransfer($node);

        try {
            $methodTransferCollection = $this->getFacade()->getMethodTransferCollectionWithRelations($classNodeTransfer);
        } catch (InvalidArgumentException $e) {
            /*
             * DocBlock exception Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepository::getDataTopOrderStatistic()
             * Delete try\catch block after fix
             */
            return;
        }

        if ($methodTransferCollection === []) {
            return;
        }

        $moduleTransfers = $this->getFacade()->getModuleTransfers($methodTransferCollection, $classNodeTransfer);

        $tableTransferCollection = $this->getFacade()->getTableTransfers($moduleTransfers, $classNodeTransfer);

        foreach ($methodTransferCollection as $methodTransfer) {
            $this->applyRule($methodTransfer, $tableTransferCollection, $classNodeTransfer);
        }
    }

    /**
     * @param \ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer $methodTransfer
     * @param array<\ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer> $tableTransferCollection
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return void
     */
    protected function applyRule(
        MethodTransfer $methodTransfer,
        array $tableTransferCollection,
        ClassNodeTransfer $classNodeTransfer
    ): void {
        $relationNames = $methodTransfer->getRelationNames();

        $moduleNames = [];
        $declaredModules = $methodTransfer->getDeclaredDependentModuleNames();

        foreach ($relationNames as $relationTableName) {
            $relationTableTransfer = $this->findRelationTableTransfer(
                $relationTableName,
                $tableTransferCollection,
            );

            if ($relationTableTransfer === null) {
                continue;
            }

            $moduleName = $relationTableTransfer->getModuleName();

            $ownerModuleName = $classNodeTransfer->getClassModuleName();

            if ($moduleName === $ownerModuleName) {
                continue;
            }

            $moduleNames[] = $moduleName;

            if (in_array($moduleName, $declaredModules)) {
                continue;
            }

            $this->addViolationMessage(
                $methodTransfer->getMethodName(),
                $relationTableName,
                $moduleName,
                $classNodeTransfer->getNode(),
            );
        }

        $extraDeclaredModules = array_diff($declaredModules, $moduleNames);

        if ($extraDeclaredModules !== []) {
            $this->addExtraDeclaredModulesViolationMessage(
                $methodTransfer->getMethodName(),
                $extraDeclaredModules,
                $classNodeTransfer->getNode(),
            );
        }
    }

    /**
     * @return \ArchitectureSniffer\PropelQuery\PropelQueryFacadeInterface
     */
    protected function getFacade(): PropelQueryFacadeInterface
    {
        if (!$this->facade) {
            $this->facade = new PropelQueryFacade();
        }

        return $this->facade;
    }

    /**
     * @param string $methodName
     * @param string $relationTableName
     * @param string $moduleName
     * @param \PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    protected function addViolationMessage(string $methodName, string $relationTableName, string $moduleName, ClassNode $node): void
    {
        $message = sprintf(
            'The Repository method `%s()` violates rule `%s`. ',
            $methodName,
            static::RULE,
        );

        $solutionMessage = sprintf(
            'Please add `@module %s` for `%s` join.',
            $moduleName,
            $relationTableName,
        );

        $message .= $solutionMessage;

        $this->addViolation($node, [$message]);
    }

    /**
     * @param string $relationTableName
     * @param array<\ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer> $tableTransfers
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer|null
     */
    protected function findRelationTableTransfer(string $relationTableName, array $tableTransfers): ?PropelSchemaTableTransfer
    {
        if ($tableTransfers === []) {
            return null;
        }

        foreach ($tableTransfers as $tableTransfer) {
            $phpName = $tableTransfer->getPhpName();

            if ($relationTableName === $phpName) {
                return $tableTransfer;
            }

            $relations = $tableTransfer->getRelations();

            foreach ($relations as $relation) {
                $relationPhpName = $relation->getPhpName();
                $referencePhpName = $relation->getRefPhpName();

                if ($relationPhpName === $relationTableName) {
                    return $tableTransfers[$relation->getTableName()];
                }

                if ($referencePhpName === $relationTableName) {
                    return $tableTransfer;
                }
            }
        }

        return null;
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     *
     * @return \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer
     */
    protected function getClassNodeTransfer(ClassNode $node): ClassNodeTransfer
    {
        $className = $node->getName();
        $classNamespace = $node->getNamespaceName();
        $classFilePath = $node->getFileName();
        $classModuleName = $this->getFacade()->getModuleName($node);

        $pathTransfer = $this->getFacade()->getPath($classFilePath);
        $reflectionNodeClass = $this->getFacade()->getReflectionClassByNode($node);

        $classNodeTransfer = new ClassNodeTransfer();

        $classNodeTransfer->setClassName($className);
        $classNodeTransfer->setClassNamespace($classNamespace);
        $classNodeTransfer->setClassFilePath($classFilePath);
        $classNodeTransfer->setClassModuleName($classModuleName);
        $classNodeTransfer->setNode($node);
        $classNodeTransfer->setReflectionClass($reflectionNodeClass);
        $classNodeTransfer->setPathTransfer($pathTransfer);

        return $classNodeTransfer;
    }

    /**
     * @param string $methodName
     * @param array<string> $extraDeclaredModules
     * @param \PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    protected function addExtraDeclaredModulesViolationMessage(string $methodName, array $extraDeclaredModules, ClassNode $node)
    {
        $message = sprintf(
            'The Repository method `%s()` violates rule `%s`. ',
            $methodName,
            static::RULE,
        );

        $solutionMessage = sprintf(
            'Please remove next declared @module: %s .',
            implode(', ', $extraDeclaredModules),
        );

        $message .= $solutionMessage;

        $this->addViolation($node, [$message]);
    }
}

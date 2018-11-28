<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Zed\Persistence\Repository;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;
use ArchitectureSniffer\PropelQuery\PropelQueryFacade;
use PHPMD\AbstractNode;
use PHPMD\Rule\MethodAware;

class RepositoryPropelQueryJoinRule extends AbstractRepositoryRule implements MethodAware
{
    public const RULE = 'All dependent modules must be declared in the DocBlock.';

    /**
     * @var \ArchitectureSniffer\PropelQuery\PropelQueryFacade
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

        $relationTableNames = $this->getFacade()->getRelationTableNames($node);

        if ($relationTableNames === []) {
            return;
        }

        $declaredDependentModuleNames = $this->getFacade()
            ->getDeclaredDependentModuleNames($node);

        $moduleTransfers = $this->getModuleTransfers(
            $relationTableNames,
            $node,
            $declaredDependentModuleNames
        );

        $ownerModuleName = $this->getFacade()->getModuleName($node);

        foreach ($relationTableNames as $relationTableName) {
            $this->applyRule($relationTableName, $moduleTransfers, $declaredDependentModuleNames, $ownerModuleName, $node); //todo: many arguments
        }
    }

    /**
     * @param string $relationTableName
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer[] $moduleTransfers
     *
     * @param string[] $docBlockModuleNames
     *
     * @return void
     */
    protected function applyRule(string $relationTableName, array $moduleTransfers, array $docBlockModuleNames, string $ownerModuleName, $node): void
    {
        $tableTransfers = [];
        //todo: lvl up
        foreach ($moduleTransfers as $moduleTransfer) {
            $tableTransfers = $this->getTableTransferCollection($moduleTransfer, $tableTransfers);
        }

        $moduleName = $this->getDependentModuleNameFromTables($relationTableName, $tableTransfers);

        if (in_array($moduleName, $docBlockModuleNames)) {
            return;
        }

        if ($moduleName === $ownerModuleName) {
            return;
        }

        $this->addViolationMessage($node, $relationTableName, $moduleName);
    }

    /**
     * @param string $joinName
     *
     * @return string
     */
    protected function getPossibleModuleNameByJoinName(string $joinName): string
    {
        return str_replace(['Spy', 'Pyz', 'Query'], '', $joinName);
    }

    /**
     * @param string $joinName
     * @param \PHPMD\AbstractNode $node
     * @param array $docBlockModuleNames
     *
     * @return array
     */
    protected function getModuleNames(string $joinName, AbstractNode $node, array $docBlockModuleNames): array
    {
        $moduleNames[] = $this->getFacade()->getQueryModuleName($node);
        $moduleNames[] = $this->getFacade()->getModuleName($node);

        $moduleNames = array_merge($moduleNames, $docBlockModuleNames);

        $moduleNames[] = $this->getPossibleModuleNameByJoinName($joinName);

        return array_unique($moduleNames);
    }

    /**
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer $moduleTransfer
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    protected function getTableTransferCollection(ModuleTransfer $moduleTransfer, array $tableTransfers): array
    {
        //todo: renames this
        $moduleTableTransferCollection = $this->getFacade()->getTablesByModule($moduleTransfer);

        foreach ($moduleTableTransferCollection as $moduleTableTransfer) {
            $tableName = $moduleTableTransfer->getTableName();
            $phpNames = $moduleTableTransfer->getPhpNames();
            $phpNames[] = str_replace('_', '', ucwords($tableName, '_'));

            if (isset($tableTransfers[$tableName])) {
                $table = $tableTransfers[$tableName];

                $phpNames = array_merge($table->getPhpNames(), $phpNames);
                $moduleName = $table->getModuleName() ?? $moduleTableTransfer->getModuleName();

                if ($moduleName !== null) {
                    $moduleTableTransfer->setModuleName($moduleName);
                }
            }

            $moduleTableTransfer->setPhpNames(array_unique($phpNames));
            $tableTransfers[$tableName] = $moduleTableTransfer;
        }

        return $tableTransfers;
    }

    /**
     * @param string $joinName
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers
     *
     * @return string|null
     */
    protected function getDependentModuleNameFromTables(string $joinName, array $tableTransfers): ?string
    {
        if ($tableTransfers === []) {
            return null;
        }

        foreach ($tableTransfers as $tableTransfer) {
            $phpName = $tableTransfer->getPhpNames();

            if (in_array($joinName, $phpName)) {
                return $tableTransfer->getModuleName();
            }
        }

        return null;
    }

    /**
     * @return \ArchitectureSniffer\PropelQuery\PropelQueryFacade
     */
    protected function getFacade(): PropelQueryFacade
    {
        if (!$this->facade) {
            $this->facade = new PropelQueryFacade();
        }

        return $this->facade;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     * @param string $joinName
     * @param string $moduleName
     *
     * @return void
     */
    protected function addViolationMessage(AbstractNode $node, string $joinName, string $moduleName): void
    {
        $message = sprintf(
            'The Repository method %s() violates rule "%s". ',
            $node->getName(),
            static::RULE
        );

        $solutionMessage = sprintf(
            'Please add @module %s for %s join.',
            $moduleName,
            $joinName
        );

        $message .= $solutionMessage;

        $this->addViolation($node, [$message]);
    }

    /**
     * @param string[] $joinNames
     * @param \PHPMD\AbstractNode $node
     * @param string[] $declaredDependentModuleNames
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    protected function getModuleTransfers(array $joinNames, AbstractNode $node, array $declaredDependentModuleNames): array
    {
        $rootPath = $this->getFacade()->getRootApplicationFolderPathByFilePath($node->getFileName());
        $moduleNames = $declaredDependentModuleNames;

        foreach ($joinNames as $joinName) {
            $moduleNames = $this->getModuleNames($joinName, $node, $moduleNames);
        }

        return $this->getFacade()->findModulesByNames($moduleNames, $rootPath);
    }
}

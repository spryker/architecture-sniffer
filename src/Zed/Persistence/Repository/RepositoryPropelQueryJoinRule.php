<?php

namespace ArchitectureSniffer\Zed\Persistence\Repository;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;
use ArchitectureSniffer\PropelQuery\PropelQueryFacade;
use PHPMD\AbstractNode;
use PHPMD\Rule\MethodAware;

class RepositoryPropelQueryJoinRule extends AbstractRepositoryRule implements MethodAware
{
    public const RULE = 'All joins must be declared in DocBlock';

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

        $joinNames = $this->getFacade()->getJoinNames($node);

        if ($joinNames === []) {
            return;
        }

        foreach ($joinNames as $joinName) {
            $this->applyRule($joinName, $node);
        }

    }

    /**
     * @param string $joinName
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    protected function applyRule(string $joinName, AbstractNode $node): void
    {
        $rootPath = $this->getFacade()->getRootApplicationFolderPathByFilePath($node->getFileName());

        $docBlockModuleNames = $this->getFacade()->getDocBlockModules($node);

        $moduleNames = $this->getModuleNames($joinName, $node, $docBlockModuleNames);

        $moduleTransfers = $this->getFacade()->findModulesByNames($moduleNames, $rootPath);

        $tableTransfers = [];

        foreach ($moduleTransfers as $moduleTransfer) {
            $tableTransfers = $this->getTableTransfers($moduleTransfer, $tableTransfers);
        }

        $moduleName = $this->findJoinModuleNameFromTables($joinName, $tableTransfers);

        if ($moduleName === null) {
            dd('Error!!!'); //todo: need to test this case
        }

        if (in_array($moduleName, $docBlockModuleNames)) {
            return;
        }

        if ($moduleName === $this->getFacade()->getModuleName($node)) {
            return;
        }

        $this->addViolationMessage($node, $joinName, $moduleName);
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
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer $module
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers
     *
     * @return array
     */
    protected function getTableTransfers(ModuleTransfer $module, array $tableTransfers): array
    {
        $moduleTableTransfer = $this->getFacade()->getTablesByModule($module);

        foreach ($moduleTableTransfer as $tableTransfer) {
            $tableName = $tableTransfer->getTableName();
            $phpNames = $tableTransfer->getPhpNames();
            $phpNames[] = str_replace('_', '', ucwords($tableName, '_'));

            if (isset($tableTransfers[$tableName])) {
                $table = $tableTransfers[$tableName];

                $phpNames = array_unique(array_merge($table->getPhpNames(), $phpNames));
            }

            $tableTransfer->setPhpNames($phpNames);
            $tableTransfers[$tableName] = $tableTransfer;
        }

        return $tableTransfers;
    }

    /**
     * @param string $joinName
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers
     *
     * @return string
     */
    protected function findJoinModuleNameFromTables(string $joinName, array $tableTransfers): ?string
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
}

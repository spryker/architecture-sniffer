<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Schema;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;
use ArchitectureSniffer\Path\PathBuilderInterface;
use ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer;
use ArchitectureSniffer\PropelQuery\Module\ModuleFinderInterface;
use ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableRelationTransfer;
use ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer;
use Symfony\Component\Finder\Finder;
use Zend\Config\Reader\ReaderInterface;

class PropelSchemaTableFinder implements PropelSchemaTableFinderInterface
{
    /**
     * @var \Zend\Config\Reader\ReaderInterface
     */
    protected $reader;

    /**
     * @var \ArchitectureSniffer\PropelQuery\Module\ModuleFinderInterface
     */
    protected $moduleFinder;

    /**
     * @var \ArchitectureSniffer\Path\PathBuilderInterface
     */
    protected $pathBuilder;

    /**
     * @param \Zend\Config\Reader\ReaderInterface $reader
     * @param \ArchitectureSniffer\Path\PathBuilderInterface $pathBuilder
     * @param \ArchitectureSniffer\PropelQuery\Module\ModuleFinderInterface $moduleFinder
     */
    public function __construct(
        ReaderInterface $reader,
        PathBuilderInterface $pathBuilder,
        ModuleFinderInterface $moduleFinder
    ) {
        $this->reader = $reader;
        $this->pathBuilder = $pathBuilder;
        $this->moduleFinder = $moduleFinder;
    }

    /**
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer[] $moduleTransfers
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    public function getTablesByModules(array $moduleTransfers, ClassNodeTransfer $classNodeTransfer): array
    {
        /**@var \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers */
        $tableTransfers = [];

        foreach ($moduleTransfers as $moduleTransfer) {
            $moduleTableTransfer = $this->getTablesByModule($moduleTransfer);

            foreach ($moduleTableTransfer as $tableTransfer) {
                $tableName = $tableTransfer->getTableName();

                if (isset($tableTransfers[$tableName])) {
                    $tableTransfer = $this->mergeTableTransfers($tableTransfers[$tableName], $tableTransfer);
                }

                $tableTransfers[$tableName] = $tableTransfer;
            }
        }

        return $this->addMissedTables($tableTransfers, $classNodeTransfer);
    }

    /**
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer $tableTransfer
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer|null
     */
    public function findTableByTableName(PropelSchemaTableTransfer $tableTransfer, ClassNodeTransfer $classNodeTransfer): ?PropelSchemaTableTransfer
    {
        $tableName = $tableTransfer->getTableName();

        $phpName = $tableTransfer->getPhpName() ?? str_replace('_', '', ucwords($tableName, '_'));

        $tableSearchPattern = '/<table name="%s".*phpName="(.*)%s".*?>/';
        $pattern = sprintf($tableSearchPattern, $tableName, $phpName);

        $finder = $this->createFinder();

        $corePropelSchemaFolderPattern = $classNodeTransfer->getPathTransfer()->getCorePath() . implode(
            DIRECTORY_SEPARATOR,
            ['*', 'src', '*', '*', '*', 'Persistence', 'Propel', 'Schema']
        );
        $projectPropelSchemaFolderPattern = $classNodeTransfer->getPathTransfer()->getProjectPath() . implode(
            DIRECTORY_SEPARATOR,
            ['*', 'Persistence', 'Propel', 'Schema']
        );

        $finder->in([
            $corePropelSchemaFolderPattern,
            $projectPropelSchemaFolderPattern,
        ])->name('*.schema.xml')->contains($pattern);

        $tableTransfers = [];

        /** @var \SplFileInfo[] $schemaFiles */
        $schemaFiles = $finder->files();

        foreach ($schemaFiles as $file) {
            $schemaFilePath = $file->getRealPath();

            $moduleName = $this->moduleFinder->getModuleNameByFilePath($schemaFilePath);
            $schema = $this->reader->fromFile($schemaFilePath);

            $tableTransfers = $this->getTables(
                $schema,
                $moduleName,
                $tableTransfers
            );
        }

        return $tableTransfers[$tableName];
    }

    /**
     * @param array $table
     *
     * @return bool
     */
    protected function isOwnerOfTable(array $table): bool
    {
        if (!isset($table['column'])) {
            return false;
        }

        return !empty(array_column($table['column'], 'primaryKey'));
    }

    /**
     * @param array $propelSchema
     * @param string $moduleName
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $propelSchemaTableTransferCollection
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    protected function getTables(array $propelSchema, string $moduleName, array $propelSchemaTableTransferCollection): array
    {
        $tables = $propelSchema['table'];

        if (isset($tables['name'])) {
            return $this->getTableTransfer($tables, $moduleName, $propelSchemaTableTransferCollection);
        }

        foreach ($tables as $table) {
            $propelSchemaTableTransferCollection = $this->getTableTransfer($table, $moduleName, $propelSchemaTableTransferCollection);
        }

        return $propelSchemaTableTransferCollection;
    }

    /**
     * @param array $table
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer $transfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer
     */
    protected function addPhpName(array $table, PropelSchemaTableTransfer $transfer): PropelSchemaTableTransfer
    {
        if (isset($table['phpName'])) {
            $transfer->setPhpName($table['phpName']);
        }

        if ($transfer->getPhpName() === null) {
            $defaultPropelPhpName = str_replace('_', '', ucwords($transfer->getTableName(), '_'));
            $transfer->setPhpName($defaultPropelPhpName);
        }

        return $transfer;
    }

    /**
     * @param array $relationTables
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer $parentTableTransfer
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    protected function addRelations(array $relationTables, PropelSchemaTableTransfer $parentTableTransfer, array $tableTransfers): array
    {
        if (isset($relationTables['foreignTable'])) {
            return $this->addRelation($relationTables, $parentTableTransfer, $tableTransfers);
        }

        foreach ($relationTables as $relationTable) {
            $tableTransfers = $this->addRelation($relationTable, $parentTableTransfer, $tableTransfers);
        }

        return $tableTransfers;
    }

    /**
     * @param array $table
     * @param string $moduleName
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    protected function getTableTransfer(array $table, string $moduleName, array $tableTransfers): array
    {
        $tableName = $table['name'];

        $transfer = $tableTransfers[$tableName] ?? new PropelSchemaTableTransfer();

        $transfer->setTableName($tableName);

        if ($this->isOwnerOfTable($table)) {
            $transfer->setModuleName($moduleName);
        }

        $transfer = $this->addPhpName($table, $transfer);
        $tableTransfers[$tableName] = $transfer;

        if (isset($table['foreign-key'])) {
            $tableTransfers = $this->addRelations($table['foreign-key'], $transfer, $tableTransfers);
        }

        return $tableTransfers;
    }

    /**
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer $mainTableTransfer
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer $additionalTableTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer
     */
    protected function mergeTableTransfers(
        PropelSchemaTableTransfer $mainTableTransfer,
        PropelSchemaTableTransfer $additionalTableTransfer
    ): PropelSchemaTableTransfer {

        $phpName = $mainTableTransfer->getPhpName() ?? $additionalTableTransfer->getPhpName();
        $moduleName = $mainTableTransfer->getModuleName() ?? $additionalTableTransfer->getModuleName();

        if ($phpName !== null) {
            $mainTableTransfer->setPhpName($phpName);
        }

        if ($moduleName !== null) {
            $mainTableTransfer->setModuleName($moduleName);
        }

        $relations = array_merge($mainTableTransfer->getRelations(), $additionalTableTransfer->getRelations());
        $mainTableTransfer->setRelations($relations);

        return $mainTableTransfer;
    }

    /**
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    protected function addMissedTables(array $tableTransfers, ClassNodeTransfer $classNodeTransfer): array
    {
        foreach ($tableTransfers as $tableTransfer) {
            if ($tableTransfer->getModuleName() !== null) {
                continue;
            }

            $missedTableTransfer = $this->findTableByTableName($tableTransfer, $classNodeTransfer);

            if ($missedTableTransfer === null) {
                continue;
            }

            $phpName = $missedTableTransfer->getPhpName() ?? $tableTransfer->getPhpName();
            $moduleName = $missedTableTransfer->getModuleName() ?? $tableTransfer->getModuleName();

            $tableTransfer->setPhpName($phpName);
            $tableTransfer->setModuleName($moduleName);
            $tableTransfers[$tableTransfer->getTableName()] = $tableTransfer;
        }

        return $tableTransfers;
    }

    /**
     * @param array $relationTable
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer $parentTableTransfer
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    protected function addRelation(array $relationTable, PropelSchemaTableTransfer $parentTableTransfer, array $tableTransfers): array
    {
        $relationTableName = $relationTable['foreignTable'];

        $relatedTableTransfer = $tableTransfers[$relationTableName] ?? new PropelSchemaTableTransfer();
        $relatedTableTransfer->setTableName($relationTableName);

        $relationTransfer = new PropelSchemaTableRelationTransfer();
        $relationTransfer->setTableName($parentTableTransfer->getTableName());

        $phpName = $relationTable['phpName'] ?? $parentTableTransfer->getPhpName();
        if ($phpName !== null) {
            $relationTransfer->setPhpName($phpName);
        }

        $refPhpName = $relationTable['refPhpName'] ?? $relatedTableTransfer->getPhpName();
        if ($refPhpName !== null) {
            $relationTransfer->setRefPhpName($refPhpName);
        }

        $relations = $relatedTableTransfer->getRelations();
        $relations[$parentTableTransfer->getTableName()] = $relationTransfer;
        $relatedTableTransfer->setRelations($relations);

        $relationTransfer = new PropelSchemaTableRelationTransfer();
        $relationTransfer->setTableName($relatedTableTransfer->getTableName());

        $phpName = $relationTable['phpName'] ?? $relatedTableTransfer->getPhpName();
        if ($phpName !== null) {
            $relationTransfer->setPhpName($phpName);
        }

        $refPhpName = $relationTable['refPhpName'] ?? $parentTableTransfer->getPhpName();

        if ($refPhpName !== null) {
            $relationTransfer->setRefPhpName($refPhpName);
        }

        $relations = $parentTableTransfer->getRelations();
        $relations[$relatedTableTransfer->getTableName()] = $relationTransfer;
        $parentTableTransfer->setRelations($relations);

        $tableTransfers[$relationTableName] = $relatedTableTransfer;
        $tableTransfers[$parentTableTransfer->getTableName()] = $parentTableTransfer;

        return $tableTransfers;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createFinder(): Finder
    {
        return new Finder();
    }

    /**
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    protected function getTablesByModule(ModuleTransfer $moduleTransfer): array
    {
        $tableTransfers = [];
        $schemaPaths = $moduleTransfer->getSchemaPaths();

        foreach ($schemaPaths as $schemaPath) {
            $moduleName = $moduleTransfer->getModuleName();
            $schema = $this->reader->fromFile($schemaPath);

            $tableTransfers = $this->getTables(
                $schema,
                $moduleName,
                $tableTransfers
            );
        }

        return $tableTransfers;
    }
}

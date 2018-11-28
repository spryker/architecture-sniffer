<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Schema;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;
use ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer;
use Zend\Config\Reader\ReaderInterface;

class PropelSchemaTableFinder implements PropelSchemaTableFinderInterface
{
    /**
     * @var \Zend\Config\Reader\ReaderInterface
     */
    protected $reader;

    /**
     * @param \Zend\Config\Reader\ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    public function getTablesByModule(ModuleTransfer $moduleTransfer): array
    {
        $tableTransfers = [];

        $moduleName = $moduleTransfer->getModuleName(); //todo: can be null

        if($moduleName === null) {
            dd('ERROR!!'); //todo: delete
        }

        foreach ($moduleTransfer->getSchemaPaths() as $schemaPath) {
            $schema = $this->reader->fromFile($schemaPath);

            $tableTransfers = $this->getTables(
                $schema,
                $moduleName,
                $tableTransfers
            );
        }

        return $tableTransfers;
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
        if (!isset($table['phpName'])) {
            return $transfer;
        }

        $phpNames = $transfer->getPhpNames();
        $phpNames[] = $table['phpName'];

        $transfer->setPhpNames(array_unique($phpNames));

        return $transfer;
    }

    /**
     * @param array $table
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[] $tableTransfers
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    protected function addRelations(array $table, array $tableTransfers): array
    {
        if (isset($table['foreignTable'])) {
            $relationTableName = $table['foreignTable'];

            $transfer = $tableTransfers[$relationTableName] ?? new PropelSchemaTableTransfer();
            $transfer->setTableName($relationTableName);

            $transfer = $this->addPhpName($table, $transfer);

            $tableTransfers[$relationTableName] = $transfer;

            return $tableTransfers;
        }

        foreach ($table as $relation) {
            $relationTableName = $relation['foreignTable'];

            $transfer = $tableTransfers[$relationTableName] ?? new PropelSchemaTableTransfer();
            $transfer->setTableName($relationTableName);

            $transfer = $this->addPhpName($relation, $transfer);

            $tableTransfers[$relationTableName] = $transfer;
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
            $transfer = $this->addReferencePhpNames($table, $transfer);
            $tableTransfers[$tableName] = $transfer;

            $tableTransfers = $this->addRelations($table['foreign-key'], $tableTransfers);
        }

        return $tableTransfers;
    }

    protected function addReferencePhpNames(array $table, PropelSchemaTableTransfer $transfer): PropelSchemaTableTransfer
    {
        //todo: found case with phpName="CompanyType" refPhpName="CompanyType" !
        $referencePhpNames = [];

        $relations = $table['foreign-key'];

        if (!isset($relations['foreignTable'])) {
            $referencePhpNames = array_column($relations, 'refPhpName');
        }

        $referencePhpNames[] = $relations['refPhpName'] ?? null;
        $referencePhpNames = array_filter($referencePhpNames);

        $phpNames = $transfer->getPhpNames();
        $phpNames = array_merge($phpNames, $referencePhpNames);

        $transfer->setPhpNames($phpNames);

        return $transfer;
    }
}

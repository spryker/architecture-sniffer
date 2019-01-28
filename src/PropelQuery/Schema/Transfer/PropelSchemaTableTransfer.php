<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Schema\Transfer;

use ArchitectureSniffer\Transfer\TransferInterface;

class PropelSchemaTableTransfer implements TransferInterface
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var string
     */
    protected $phpName;

    /**
     * @var \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableRelationTransfer[]
     */
    protected $relations = [];

    /**
     * @return string|null
     */
    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     *
     * @return void
     */
    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    /**
     * @return string|null
     */
    public function getModuleName(): ?string
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     *
     * @return void
     */
    public function setModuleName(string $moduleName): void
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return string|null
     */
    public function getPhpName(): ?string
    {
        return $this->phpName;
    }

    /**
     * @param string $phpName
     *
     * @return void
     */
    public function setPhpName(string $phpName): void
    {
        $this->phpName = $phpName;
    }

    /**
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableRelationTransfer[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @param \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableRelationTransfer[] $relations
     *
     * @return void
     */
    public function setRelations(array $relations): void
    {
        $this->relations = $relations;
    }
}

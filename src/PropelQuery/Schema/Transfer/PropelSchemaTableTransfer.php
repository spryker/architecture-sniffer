<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Schema\Transfer;

class PropelSchemaTableTransfer
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
     * @var string[]
     */
    protected $phpNames = [];

    /**
     * @return string
     */
    public function getTableName(): string
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
     * @return string
     */
    public function getModuleName(): string
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
     * @return string[]
     */
    public function getPhpNames(): array
    {
        return $this->phpNames;
    }

    /**
     * @param string[] $phpNames
     *
     * @return void
     */
    public function setPhpNames(array $phpNames): void
    {
        $this->phpNames = $phpNames;
    }
}

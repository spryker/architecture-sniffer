<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Module\Transfer;

use ArchitectureSniffer\Transfer\TransferInterface;

class ModuleTransfer implements TransferInterface
{
    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var string[]
     */
    protected $modulePaths;

    /**
     * @var string[]
     */
    protected $schemaPaths;

    /**
     * @var bool
     */
    protected $exists = false;

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
    public function getModulePaths(): array
    {
        return $this->modulePaths;
    }

    /**
     * @param string[] $modulePaths
     *
     * @return void
     */
    public function setModulePaths(array $modulePaths): void
    {
        $this->modulePaths = $modulePaths;
        $this->exists = true;
    }

    /**
     * @return string[]
     */
    public function getSchemaPaths(): array
    {
        return $this->schemaPaths;
    }

    /**
     * @param string[] $schemaPaths
     *
     * @return void
     */
    public function setSchemaPaths(array $schemaPaths): void
    {
        $this->schemaPaths = $schemaPaths;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }
}

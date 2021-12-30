<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @var array<string>
     */
    protected $modulePaths;

    /**
     * @var array<string>
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
     * @return array<string>
     */
    public function getModulePaths(): array
    {
        return $this->modulePaths;
    }

    /**
     * @param array<string> $modulePaths
     *
     * @return void
     */
    public function setModulePaths(array $modulePaths): void
    {
        $this->modulePaths = $modulePaths;
        $this->exists = true;
    }

    /**
     * @return array<string>
     */
    public function getSchemaPaths(): array
    {
        return $this->schemaPaths;
    }

    /**
     * @param array<string> $schemaPaths
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Path\Transfer;

use ArchitectureSniffer\Transfer\TransferInterface;

class PathTransfer implements TransferInterface
{
    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var string
     */
    protected $corePath;

    /**
     * @var string
     */
    protected $projectPath;

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @param string $rootPath
     *
     * @return void
     */
    public function setRootPath(string $rootPath): void
    {
        $this->rootPath = $rootPath;
    }

    /**
     * @return string
     */
    public function getCorePath(): string
    {
        return $this->corePath;
    }

    /**
     * @param string $corePath
     *
     * @return void
     */
    public function setCorePath(string $corePath): void
    {
        $this->corePath = $corePath;
    }

    /**
     * @return string
     */
    public function getProjectPath(): string
    {
        return $this->projectPath;
    }

    /**
     * @param string $projectPath
     *
     * @return void
     */
    public function setProjectPath(string $projectPath): void
    {
        $this->projectPath = $projectPath;
    }
}

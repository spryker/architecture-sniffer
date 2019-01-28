<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Path;

use ArchitectureSniffer\Path\Transfer\PathTransfer;

interface PathBuilderInterface
{
    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getRootApplicationDirectoryPathByFilePath(string $filePath): string;

    /**
     * @param string $moduleName
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return string
     */
    public function getCoreModulePathByModuleName(string $moduleName, PathTransfer $pathTransfer): string;

    /**
     * @param string $moduleName
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return string
     */
    public function getProjectModulePathByModuleName(string $moduleName, PathTransfer $pathTransfer): string;

    /**
     * @param string $modulePath
     *
     * @return string
     */
    public function getSchemaPath(string $modulePath): string;

    /**
     * @param string $rootPath
     *
     * @return string
     */
    public function getProjectPath(string $rootPath): string;

    /**
     * @param string $rootPath
     *
     * @return string
     */
    public function getCorePath(string $rootPath): string;

    /**
     * @param string $filePath
     *
     * @return \ArchitectureSniffer\Path\Transfer\PathTransfer
     */
    public function getPath(string $filePath): PathTransfer;
}

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Path;

interface PathBuilderInterface
{
    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getRootApplicationFolderPathByFilePath(string $filePath): string;

    /**
     * @param string $moduleName
     * @param string $rootPath
     *
     * @return string
     */
    public function getCoreModulePathByModuleName(string $moduleName, string $rootPath): string;

    /**
     * @param string $moduleName
     * @param string $rootPath
     *
     * @return string
     */
    public function getProjectModulePathByModuleName(string $moduleName, string $rootPath): string;

    /**
     * @param string $modulePath
     *
     * @return string
     */
    public function getSchemaFolderPath(string $modulePath): string;
}

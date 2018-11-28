<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Path;

class PathBuilder implements PathBuilderInterface
{
    protected const CONST_NAME_APPLICATION_ROOT_DIR = 'APPLICATION_ROOT_DIR';

    protected const PATTERN_PATH_MODULE_CORE = 'vendor/spryker/spryker/Bundles/%1$s/src/Spryker/Zed/%1$s';
    protected const PATTERN_PATH_MODULE_PROJECT = 'src/Pyz/Zed/%s';

    protected const PATTERN_PATH_MODULE_SCHEMA_FOLDER = 'Persistence/Propel/Schema';

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getRootApplicationFolderPathByFilePath(string $filePath): string
    {
        if ($this->isApplicationRootDefined()) {
            return ${static::CONST_NAME_APPLICATION_ROOT_DIR};
        }

        $vendorPosition = strpos($filePath, 'vendor');

        if ($vendorPosition !== false) {
            return substr($filePath, 0, $vendorPosition);
        }

        $sourcePosition = strpos($filePath, 'src');

        return substr($filePath, 0, $sourcePosition);
    }

    /**
     * @param string $moduleName
     * @param string $rootPath
     *
     * @return string
     */
    public function getCoreModulePathByModuleName(string $moduleName, string $rootPath): string
    {
        return $rootPath . sprintf(static::PATTERN_PATH_MODULE_CORE, $moduleName);
    }

    /**
     * @param string $moduleName
     * @param string $rootPath
     *
     * @return string
     */
    public function getProjectModulePathByModuleName(string $moduleName, string $rootPath): string
    {
        return $rootPath . sprintf(static::PATTERN_PATH_MODULE_PROJECT, $moduleName);
    }

    /**
     * @param string $modulePath
     *
     * @return string
     */
    public function getSchemaFolderPath(string $modulePath): string
    {
        return $modulePath . PHP_EOL . static::PATTERN_PATH_MODULE_SCHEMA_FOLDER;
    }

    /**
     * @return bool
     */
    protected function isApplicationRootDefined(): bool
    {
        return defined(static::CONST_NAME_APPLICATION_ROOT_DIR);
    }
}

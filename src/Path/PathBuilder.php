<?php /** @noinspection ALL */

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Path;

use ArchitectureSniffer\Path\Transfer\PathTransfer;
use Laminas\Filter\Word\CamelCaseToSeparator;

class PathBuilder implements PathBuilderInterface
{
    protected const CONST_NAME_APPLICATION_ROOT_DIR = 'APPLICATION_ROOT_DIR';

    protected const PATTERN_PATH_MODULE_SCHEMA_FOLDER = 'Persistence/Propel/Schema';

    /**
     * @param string $filePath
     *
     * @return \ArchitectureSniffer\Path\Transfer\PathTransfer
     */
    public function getPath(string $filePath): PathTransfer
    {
        $rootPath = $this->getRootApplicationDirectoryPathByFilePath($filePath);
        $corePath = $this->getCorePath($filePath);
        $projectPath = $this->getProjectPath($filePath);

        $pathTransfer = new PathTransfer();

        $pathTransfer->setRootPath($rootPath);
        $pathTransfer->setCorePath($corePath);
        $pathTransfer->setProjectPath($projectPath);

        return $pathTransfer;
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getRootApplicationDirectoryPathByFilePath(string $filePath): string
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
     * @param string $filePath
     *
     * @return string
     */
    public function getProjectPath(string $filePath): string
    {
        $rootPath = $this->getRootApplicationDirectoryPathByFilePath($filePath);

        $path = rtrim($rootPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $path .= implode(DIRECTORY_SEPARATOR, [
            'src',
            'Pyz',
            'Zed',
        ]);

        return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getCorePath(string $filePath): string
    {
        $rootPath = $this->getRootApplicationDirectoryPathByFilePath($filePath);
        $corePath = mb_substr($filePath, 0, mb_strpos($filePath, 'src'));
        $corePath = rtrim($corePath, DIRECTORY_SEPARATOR);
        $corePath = explode(DIRECTORY_SEPARATOR, $corePath);

        array_pop($corePath);
        $corePath = implode(DIRECTORY_SEPARATOR, $corePath);

        return rtrim($corePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $moduleName
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return string
     */
    public function getCoreModulePathByModuleName(string $moduleName, PathTransfer $pathTransfer): string
    {
        $moduleDirectoryName = $moduleName;

        if (!strpos($pathTransfer->getCorePath(), 'Bundles')) {
            $moduleDirectoryName = $this->formatCamelCaseToSnakeCase($moduleName);
        }

        $coreModulePattern = implode(DIRECTORY_SEPARATOR, [
            '%s',
            'src',
            'Spryker',
            'Zed',
            '%s',
        ]);

        return $pathTransfer->getCorePath() . sprintf($coreModulePattern, $moduleDirectoryName, $moduleName) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $moduleName
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return string
     */
    public function getProjectModulePathByModuleName(string $moduleName, PathTransfer $pathTransfer): string
    {
        return $pathTransfer->getProjectPath() . $moduleName . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $modulePath
     *
     * @return string
     */
    public function getSchemaPath(string $modulePath): string
    {
        return $modulePath . DIRECTORY_SEPARATOR . static::PATTERN_PATH_MODULE_SCHEMA_FOLDER . DIRECTORY_SEPARATOR;
    }

    /**
     * @return bool
     */
    protected function isApplicationRootDefined(): bool
    {
        return defined(static::CONST_NAME_APPLICATION_ROOT_DIR);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function formatCamelCaseToSnakeCase(string $string)
    {
        return strtolower((new CamelCaseToSeparator('-'))->filter($string));
    }
}

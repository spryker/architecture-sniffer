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
    /**
     * @var string
     */
    protected const CONST_NAME_APPLICATION_ROOT_DIR = 'APPLICATION_ROOT_DIR';

    /**
     * @var string
     */
    protected const PATTERN_PATH_MODULE_SCHEMA_FOLDER = 'Persistence/Propel/Schema';

    /**
     * @var string
     */
    protected const string PATTERN_IN_TREE_CORE_PATH = '#^(.*[/\\\\]src[/\\\\](?:Spryker|SprykerShop|SprykerFeature|SprykerEco))[/\\\\][^/\\\\]+[/\\\\]src[/\\\\]#';

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
        $inTreeCorePath = $this->findInTreeCorePath($filePath);

        if ($inTreeCorePath !== null) {
            return $inTreeCorePath;
        }

        $rootPath = $this->getRootApplicationDirectoryPathByFilePath($filePath);
        $corePath = mb_substr($filePath, 0, mb_strpos($filePath, 'src'));
        $corePath = rtrim($corePath, DIRECTORY_SEPARATOR);
        $corePath = explode(DIRECTORY_SEPARATOR, $corePath);

        array_pop($corePath);
        $corePath = implode(DIRECTORY_SEPARATOR, $corePath);

        return rtrim($corePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * Detects the in-tree core layout (`<root>/src/<Org>/<Module>/src/<Org>/<Application>/<Module>/...`,
     * e.g. the suite repository) where core modules live next to the analysed file instead of
     * `vendor/spryker/<module>`. Returns the org directory containing the PascalCase module
     * directories, or null for the classic layout.
     *
     * @return string|null
     */
    protected function findInTreeCorePath(string $filePath): ?string
    {
        if (preg_match(static::PATTERN_IN_TREE_CORE_PATH, $filePath, $matches) !== 1) {
            return null;
        }

        return $matches[1] . DIRECTORY_SEPARATOR;
    }

    public function getCoreModulePathByModuleName(string $moduleName, PathTransfer $pathTransfer): string
    {
        $coreModulePattern = implode(DIRECTORY_SEPARATOR, [
            '%s',
            'src',
            'Spryker',
            'Zed',
            '%s',
        ]);

        $moduleDirectoryNames = [
            $this->formatCamelCaseToSnakeCase($moduleName),
            $moduleName,
        ];

        foreach ($moduleDirectoryNames as $moduleDirectoryName) {
            $coreModulePath = $pathTransfer->getCorePath() . sprintf($coreModulePattern, $moduleDirectoryName, $moduleName) . DIRECTORY_SEPARATOR;

            // The exact-case check keeps the snake-case and PascalCase candidates apart
            // on case-insensitive file systems (macOS), where file_exists() alone would
            // match either spelling.
            if ($this->directoryExistsWithExactCase($pathTransfer->getCorePath() . $moduleDirectoryName) && file_exists($coreModulePath)) {
                return $coreModulePath;
            }
        }

        return $pathTransfer->getCorePath() . sprintf($coreModulePattern, $this->formatCamelCaseToSnakeCase($moduleName), $moduleName) . DIRECTORY_SEPARATOR;
    }

    public function getProjectModulePathByModuleName(string $moduleName, PathTransfer $pathTransfer): string
    {
        $classicModulePath = $pathTransfer->getProjectPath() . $moduleName . DIRECTORY_SEPARATOR;

        if (file_exists($classicModulePath)) {
            return $classicModulePath;
        }

        $splitModulePath = $this->buildSplitProjectModulePath($moduleName, $pathTransfer);

        if ($splitModulePath !== null && file_exists($splitModulePath)) {
            return $splitModulePath;
        }

        return $classicModulePath;
    }

    /**
     * Module-split project layout: `<root>/src/Pyz/<Module>/src/Pyz/Zed/<Module>/...`
     * (the classic layout is `<root>/src/Pyz/Zed/<Module>/...`).
     *
     * @return string|null
     */
    protected function buildSplitProjectModulePath(string $moduleName, PathTransfer $pathTransfer): ?string
    {
        $projectPath = $pathTransfer->getProjectPath();
        $zedSuffix = 'Zed' . DIRECTORY_SEPARATOR;

        if (substr($projectPath, -strlen($zedSuffix)) !== $zedSuffix) {
            return null;
        }

        $projectBasePath = substr($projectPath, 0, -strlen($zedSuffix));

        return $projectBasePath . $moduleName . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, [
            'src',
            'Pyz',
            'Zed',
            $moduleName,
        ]) . DIRECTORY_SEPARATOR;
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

    protected function directoryExistsWithExactCase(string $directoryPath): bool
    {
        $directoryPath = rtrim($directoryPath, DIRECTORY_SEPARATOR);
        $parentDirectoryPath = dirname($directoryPath);

        if (!is_dir($parentDirectoryPath)) {
            return false;
        }

        return in_array(basename($directoryPath), scandir($parentDirectoryPath) ?: [], true);
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

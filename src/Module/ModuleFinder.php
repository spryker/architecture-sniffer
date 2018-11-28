<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Module;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;
use ArchitectureSniffer\Path\PathBuilderInterface;
use Symfony\Component\Finder\Finder;

class ModuleFinder implements ModuleFinderInterface
{
    /**
     * @var \ArchitectureSniffer\Path\PathBuilderInterface
     */
    protected $pathBuilder;

    /**
     * @param \ArchitectureSniffer\Path\PathBuilderInterface $pathBuilder
     */
    public function __construct(PathBuilderInterface $pathBuilder)
    {
        $this->pathBuilder = $pathBuilder;
    }

    /**
     * @param string $moduleName
     * @param string $rootPath
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer
     */
    public function findModuleByName(string $moduleName, string $rootPath): ModuleTransfer
    {
        $moduleTransfer = $this->createModuleTransfer();
        $moduleTransfer->setModuleName($moduleName);

        $coreModulePath = $this->getCoreModulePath($moduleName, $rootPath);
        $projectModulePath = $this->getProjectModulePath($moduleName, $rootPath);

        $modulePaths = array_filter([
            $coreModulePath,
            $projectModulePath,
        ]);

        if ($modulePaths === []) {
            return $moduleTransfer;
        }

        $schemas = $this->getModuleSchemaPaths($modulePaths);

        $moduleTransfer->setModulePaths($modulePaths);
        $moduleTransfer->setSchemaPaths($schemas);

        return $moduleTransfer;
    }

    /**
     * @param string[] $moduleNames
     * @param string $rootPath
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function findModulesByNames(array $moduleNames, string $rootPath): array
    {
        $moduleTransferCollection = [];

        foreach ($moduleNames as $moduleName) {
            $moduleTransfer = $this->findModuleByName($moduleName, $rootPath);

            if (!$moduleTransfer->exists()) {
                continue;
            }

            $moduleTransferCollection[] = $moduleTransfer;
        }

        return $moduleTransferCollection;
    }

    /**
     * @param array $modulePaths
     *
     * @return string[]
     */
    protected function getModuleSchemaPaths(array $modulePaths): array
    {
        $schemaPaths = [];

        $fileFinder = $this->createFinder()->in($modulePaths)->name('*.schema.xml');

        /*** @var \Symfony\Component\Finder\SplFileInfo[] $files */
        $files = $fileFinder->files();

        foreach ($files as $file) {
            $schemaPaths[] = $file->getRealPath();
        }

        return $schemaPaths;
    }

    /**
     * @param string $moduleName
     * @param string $rootPath
     *
     * @return string|null
     */
    protected function getCoreModulePath(string $moduleName, string $rootPath): ?string
    {
        $path = $this->pathBuilder->getCoreModulePathByModuleName($moduleName, $rootPath);

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }

    /**
     * @param string $moduleName
     * @param string $rootPath
     *
     * @return string|null
     */
    protected function getProjectModulePath(string $moduleName, string $rootPath): ?string
    {
        $path = $this->pathBuilder->getProjectModulePathByModuleName($moduleName, $rootPath);

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }

    /**
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer
     */
    protected function createModuleTransfer(): ModuleTransfer
    {
        return new ModuleTransfer();
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createFinder(): Finder
    {
        return new Finder();
    }
}

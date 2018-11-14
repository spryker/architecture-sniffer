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
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \ArchitectureSniffer\Path\PathBuilderInterface $pathBuilder
     * @param \Symfony\Component\Finder\Finder $finder
     */
    public function __construct(PathBuilderInterface $pathBuilder, Finder $finder)
    {
        $this->pathBuilder = $pathBuilder;
        $this->finder = $finder;
    }

    /**
     * @param string $moduleName
     * @param string $rootPath
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer
     */
    public function findModuleByName(string $moduleName, string $rootPath): ModuleTransfer
    {
        $module = $this->createModuleTransfer();
        $module->setModuleName($moduleName);

        $coreModulePath = $this->getCoreModulePath($moduleName, $rootPath);
        $projectModulePath = $this->getProjectModulePath($moduleName, $rootPath);

        $modulePaths = array_filter([
            $coreModulePath,
            $projectModulePath,
        ]);

        if ($modulePaths === []) {
            return $module;
        }

        $schemas = $this->getModuleSchemaPaths($modulePaths);

        $module->setModulePaths($modulePaths);
        $module->setSchemaPaths($schemas);

        return $module;
    }

    /**
     * @param array $moduleNames
     * @param string $rootPath
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function findModulesByNames(array $moduleNames, string $rootPath): array
    {
        $modules = [];

        foreach ($moduleNames as $moduleName) {
            $module = $this->findModuleByName($moduleName, $rootPath);

            if (!$module->isExist()) {
                continue;
            }

            $modules[] = $module;
        }

        return $modules;
    }

    /**
     * @param array $modulePaths
     *
     * @return string[]
     */
    protected function getModuleSchemaPaths(array $modulePaths): array
    {
        $schemaPaths = [];

        $this->finder;
        $fileFinder = $this->finder->in($modulePaths)->name('*.schema.xml');

        foreach ($fileFinder->files() as $file) {
            /**
             * @var \Symfony\Component\Finder\SplFileInfo $file
             */
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
}

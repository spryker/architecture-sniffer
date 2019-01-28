<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Module;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;
use ArchitectureSniffer\Path\PathBuilderInterface;
use ArchitectureSniffer\Path\Transfer\PathTransfer;
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
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer
     */
    public function findModuleByName(string $moduleName, PathTransfer $pathTransfer): ModuleTransfer
    {
        $moduleTransfer = $this->createModuleTransfer();
        $moduleTransfer->setModuleName($moduleName);

        $coreModulePath = $this->getCoreModulePath($moduleName, $pathTransfer);
        $projectModulePath = $this->getProjectModulePath($moduleName, $pathTransfer);

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
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function findModulesByNames(array $moduleNames, PathTransfer $pathTransfer): array
    {
        $moduleTransferCollection = [];

        foreach ($moduleNames as $moduleName) {
            $moduleTransfer = $this->findModuleByName($moduleName, $pathTransfer);

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
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return string|null
     */
    protected function getCoreModulePath(string $moduleName, PathTransfer $pathTransfer): ?string
    {
        $path = $this->pathBuilder->getCoreModulePathByModuleName($moduleName, $pathTransfer);

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }

    /**
     * @param string $moduleName
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return string|null
     */
    protected function getProjectModulePath(string $moduleName, PathTransfer $pathTransfer): ?string
    {
        $path = $this->pathBuilder->getProjectModulePathByModuleName($moduleName, $pathTransfer);

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

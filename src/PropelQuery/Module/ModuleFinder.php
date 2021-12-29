<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery\Module;

use ArchitectureSniffer\Module\ModuleFinderInterface as ArchitectureSnifferModuleFinderInterface;
use ArchitectureSniffer\Path\PathBuilderInterface;
use ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer;
use PHPStan\BetterReflection\Reflector\ClassReflector;

class ModuleFinder implements ModuleFinderInterface
{
    /**
     * @var \ArchitectureSniffer\Module\ModuleFinderInterface
     */
    protected $moduleFinder;

    /**
     * @var \PHPStan\BetterReflection\Reflector\ClassReflector
     */
    protected $classReflector;

    /**
     * @var \ArchitectureSniffer\Path\PathBuilderInterface
     */
    protected $pathBuilder;

    /**
     * @param \ArchitectureSniffer\Module\ModuleFinderInterface $moduleFinder
     * @param \PHPStan\BetterReflection\Reflector\ClassReflector $classReflector
     * @param \ArchitectureSniffer\Path\PathBuilderInterface $pathBuilder
     */
    public function __construct(
        ArchitectureSnifferModuleFinderInterface $moduleFinder,
        ClassReflector $classReflector,
        PathBuilderInterface $pathBuilder
    ) {
        $this->moduleFinder = $moduleFinder;
        $this->classReflector = $classReflector;
        $this->pathBuilder = $pathBuilder;
    }

    /**
     * @param array<\ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer> $methodTransferCollection
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<\ArchitectureSniffer\Module\Transfer\ModuleTransfer>
     */
    public function getModuleTransfers(array $methodTransferCollection, ClassNodeTransfer $classNodeTransfer): array
    {
        $moduleNames = $this->getModuleNames($methodTransferCollection, $classNodeTransfer);

        return $this->getModuleTransfersByModuleNames($moduleNames, $classNodeTransfer);
    }

    /**
     * @param array<string> $moduleNames
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<\ArchitectureSniffer\Module\Transfer\ModuleTransfer>
     */
    public function getModuleTransfersByModuleNames(array $moduleNames, ClassNodeTransfer $classNodeTransfer): array
    {
        return $this->moduleFinder->findModulesByNames(
            $moduleNames,
            $classNodeTransfer->getPathTransfer(),
        );
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getModuleNameByFilePath(string $filePath): string
    {
        $modulePath = str_replace([
            $this->pathBuilder->getCorePath($filePath),
            $this->pathBuilder->getProjectPath($filePath),
        ], '', $filePath);

        $modulePath = explode(DIRECTORY_SEPARATOR, $modulePath);

        return ucfirst(array_shift($modulePath));
    }

    /**
     * @param array<\ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer> $methodTransferCollection
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<string>
     */
    protected function getModuleNames(array $methodTransferCollection, ClassNodeTransfer $classNodeTransfer): array
    {
        $moduleNames = [];

        $parentModuleName = $classNodeTransfer->getClassModuleName();

        $queryModuleNames = $this->getQueryNames($methodTransferCollection, $classNodeTransfer);

        foreach ($methodTransferCollection as $methodTransfer) {
            $moduleNames[] = $methodTransfer->getRelationNames();
            $moduleNames[] = $methodTransfer->getDeclaredDependentModuleNames();

            $moduleNames[] = str_replace(
                ['get', 'create', 'Spy', 'Pyz', 'Query'],
                '',
                $methodTransfer->getQueryNames(),
            );
        }

        $moduleNames = array_merge($queryModuleNames, ...$moduleNames);
        $moduleNames[] = $parentModuleName;

        $moduleNames = array_unique($moduleNames);

        return array_filter($moduleNames);
    }

    /**
     * @param array $methodTransferCollection
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<string>
     */
    protected function getQueryNames(array $methodTransferCollection, ClassNodeTransfer $classNodeTransfer): array
    {
        $queryNames = [];

        foreach ($methodTransferCollection as $methodTransfer) {
            $queryNames[] = $methodTransfer->getQueryNames();
        }

        $queryNames = array_merge(...$queryNames);
        $queryNames = array_unique($queryNames);
        $queryNames = array_filter($queryNames);

        return $this->getModuleNamesByQueryNames($queryNames, $classNodeTransfer);
    }

    /**
     * @param array<string> $queryNames
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<string>
     */
    protected function getModuleNamesByQueryNames(array $queryNames, ClassNodeTransfer $classNodeTransfer): array
    {
        $persistenceFactoryClassName = $this->getPersistenceFactoryClassName($classNodeTransfer);
        $reflectionPersistenceFactoryClass = $this->classReflector->reflect($persistenceFactoryClassName);

        $queryModuleNames = [];
        foreach ($queryNames as $queryName) {
            $returnTypes = $reflectionPersistenceFactoryClass->getMethod($queryName)->getDocBlockReturnTypes();

            $returnType = array_shift($returnTypes);
            $returnType = $returnType->__toString();

            $queryModuleName = str_replace('\\Orm\\Zed\\', '', $returnType);
            $queryModuleName = explode('\\', $queryModuleName);
            $queryModuleNames[] = array_shift($queryModuleName);
        }

        return array_unique($queryModuleNames);
    }

    /**
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return string
     */
    protected function getPersistenceFactoryClassName(ClassNodeTransfer $classNodeTransfer): string
    {
        $parentModuleName = $classNodeTransfer->getClassModuleName();
        $nodeNamespace = $classNodeTransfer->getClassNamespace();

        return $nodeNamespace . '\\' . $parentModuleName . 'PersistenceFactory';
    }
}

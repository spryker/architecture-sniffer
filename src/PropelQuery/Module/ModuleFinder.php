<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery\Module;

use ArchitectureSniffer\Module\ModuleFinderInterface as ArchitectureSnifferModuleFinderInterface;
use ArchitectureSniffer\Path\PathBuilderInterface;
use ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer;
use PHPStan\BetterReflection\Reflection\ReflectionMethod;
use PHPStan\BetterReflection\Reflection\ReflectionNamedType;
use PHPStan\BetterReflection\Reflector\DefaultReflector;

class ModuleFinder implements ModuleFinderInterface
{
    /**
     * @var \ArchitectureSniffer\Module\ModuleFinderInterface
     */
    protected $moduleFinder;

    /**
     * @var \PHPStan\BetterReflection\Reflector\DefaultReflector
     */
    protected $classReflector;

    /**
     * @var \ArchitectureSniffer\Path\PathBuilderInterface
     */
    protected $pathBuilder;

    /**
     * @param \ArchitectureSniffer\Module\ModuleFinderInterface $moduleFinder
     * @param \PHPStan\BetterReflection\Reflector\DefaultReflector $classReflector
     * @param \ArchitectureSniffer\Path\PathBuilderInterface $pathBuilder
     */
    public function __construct(
        ArchitectureSnifferModuleFinderInterface $moduleFinder,
        DefaultReflector $classReflector,
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
        $reflectionPersistenceFactoryClass = $this->classReflector->reflectClass($persistenceFactoryClassName);

        $queryModuleNames = [];
        foreach ($queryNames as $queryName) {
            $reflectionMethod = $reflectionPersistenceFactoryClass->getMethod($queryName);

            if ($reflectionMethod === null) {
                continue;
            }

            $returnType = $this->getReturnTypeName($reflectionMethod);

            if ($returnType === null) {
                continue;
            }

            $queryModuleName = str_replace('Orm\\Zed\\', '', ltrim($returnType, '\\'));
            $queryModuleName = explode('\\', $queryModuleName);
            $queryModuleNames[] = array_shift($queryModuleName);
        }

        return array_unique($queryModuleNames);
    }

    /**
     * The BetterReflection bundled with PHPStan 2.x removed `getDocBlockReturnTypes()`,
     * so the `@return` tag is read from the raw doc comment, falling back to the
     * declared native return type.
     *
     * @return string|null
     */
    protected function getReturnTypeName(ReflectionMethod $reflectionMethod): ?string
    {
        $docComment = (string)$reflectionMethod->getDocComment();

        if ($docComment !== '' && preg_match('/@return\s+([^\s|]+)/', $docComment, $matches)) {
            return $matches[1];
        }

        $returnType = $reflectionMethod->getReturnType();

        if ($returnType instanceof ReflectionNamedType) {
            return $returnType->getName();
        }

        return null;
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

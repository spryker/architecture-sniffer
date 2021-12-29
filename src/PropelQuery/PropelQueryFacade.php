<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery;

use ArchitectureSniffer\Path\Transfer\PathTransfer;
use ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer;
use PHPMD\AbstractNode;
use PHPStan\BetterReflection\Reflection\ReflectionClass;

class PropelQueryFacade implements PropelQueryFacadeInterface
{
    /**
     * @var \ArchitectureSniffer\PropelQuery\PropelQueryFactory
     */
    protected $factory;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getModuleName(AbstractNode $node): string
    {
        return $this->getFactory()
            ->createNodeReader()
            ->getModuleName($node);
    }

    /**
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<\ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer>
     */
    public function getMethodTransferCollectionWithRelations(ClassNodeTransfer $classNodeTransfer): array
    {
        return $this->getFactory()
            ->createMethodFinder()
            ->getMethodTransferCollectionWithRelations($classNodeTransfer);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \PHPStan\BetterReflection\Reflection\ReflectionClass
     */
    public function getReflectionClassByNode(AbstractNode $node): ReflectionClass
    {
        return $this->getFactory()
            ->createNodeReader()
            ->getReflectionClassByNode($node);
    }

    /**
     * @param array $methodTransferCollection
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<\ArchitectureSniffer\Module\Transfer\ModuleTransfer>
     */
    public function getModuleTransfers(array $methodTransferCollection, ClassNodeTransfer $classNodeTransfer): array
    {
        return $this->getFactory()
            ->createModuleFinder()
            ->getModuleTransfers($methodTransferCollection, $classNodeTransfer);
    }

    /**
     * @param array<\ArchitectureSniffer\Module\Transfer\ModuleTransfer> $moduleTransfers
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<\ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer>
     */
    public function getTableTransfers(array $moduleTransfers, ClassNodeTransfer $classNodeTransfer): array
    {
        return $this->getFactory()
            ->createPropelSchemaTableFinder()
            ->getTablesByModules($moduleTransfers, $classNodeTransfer);
    }

    /**
     * @param string $filePath
     *
     * @return \ArchitectureSniffer\Path\Transfer\PathTransfer
     */
    public function getPath(string $filePath): PathTransfer
    {
        return $this->getFactory()->createPathBuilder()->getPath($filePath);
    }

    /**
     * @return \ArchitectureSniffer\PropelQuery\PropelQueryFactory
     */
    protected function getFactory(): PropelQueryFactory
    {
        if (!$this->factory) {
            $this->factory = new PropelQueryFactory();
        }

        return $this->factory;
    }
}

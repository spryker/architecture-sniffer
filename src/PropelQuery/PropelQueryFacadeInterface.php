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

interface PropelQueryFacadeInterface
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getModuleName(AbstractNode $node): string;

    /**
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer[]
     */
    public function getMethodTransferCollectionWithRelations(ClassNodeTransfer $classNodeTransfer): array;

    /**
     * @param string $filePath
     *
     * @return \ArchitectureSniffer\Path\Transfer\PathTransfer
     */
    public function getPath(string $filePath): PathTransfer;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \PHPStan\BetterReflection\Reflection\ReflectionClass
     */
    public function getReflectionClassByNode(AbstractNode $node): ReflectionClass;

    /**
     * @param array $methodTransferCollection
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function getModuleTransfers(array $methodTransferCollection, ClassNodeTransfer $classNodeTransfer): array;

    /**
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer[] $moduleTransfers
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    public function getTableTransfers(array $moduleTransfers, ClassNodeTransfer $classNodeTransfer): array;
}

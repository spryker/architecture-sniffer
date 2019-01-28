<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Module;

use ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer;

interface ModuleFinderInterface
{
    /**
     * @param \ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer[] $methodTransferCollection
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function getModuleTransfers(array $methodTransferCollection, ClassNodeTransfer $classNodeTransfer): array;

    /**
     * @param string[] $moduleNames
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function getModuleTransfersByModuleNames(array $moduleNames, ClassNodeTransfer $classNodeTransfer): array;

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getModuleNameByFilePath(string $filePath): string;
}

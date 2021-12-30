<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery\Module;

use ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer;

interface ModuleFinderInterface
{
    /**
     * @param array<\ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer> $methodTransferCollection
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<\ArchitectureSniffer\Module\Transfer\ModuleTransfer>
     */
    public function getModuleTransfers(array $methodTransferCollection, ClassNodeTransfer $classNodeTransfer): array;

    /**
     * @param array<string> $moduleNames
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<\ArchitectureSniffer\Module\Transfer\ModuleTransfer>
     */
    public function getModuleTransfersByModuleNames(array $moduleNames, ClassNodeTransfer $classNodeTransfer): array;

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getModuleNameByFilePath(string $filePath): string;
}

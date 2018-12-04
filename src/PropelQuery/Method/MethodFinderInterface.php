<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Method;

use ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer;

interface MethodFinderInterface
{
    /**
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer[]
     */
    public function getMethodTransferCollectionWithRelations(ClassNodeTransfer $classNodeTransfer): array;
}

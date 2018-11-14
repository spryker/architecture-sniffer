<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Schema;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;

interface PropelSchemaTableFinderInterface
{
    /**
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    public function getTablesByModule(ModuleTransfer $moduleTransfer): array;
}

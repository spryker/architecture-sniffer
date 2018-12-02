<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Module;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;
use ArchitectureSniffer\Path\Transfer\PathTransfer;

interface ModuleFinderInterface
{
    /**
     * @param string $moduleName
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer
     */
    public function findModuleByName(string $moduleName, PathTransfer $pathTransfer): ModuleTransfer;

    /**
     * @param array $moduleNames
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function findModulesByNames(array $moduleNames, PathTransfer $pathTransfer): array;
}

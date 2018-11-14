<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Module;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;

interface ModuleFinderInterface
{
    /**
     * @param string $moduleName
     * @param string $rootPath
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer
     */
    public function findModuleByName(string $moduleName, string $rootPath): ModuleTransfer;

    /**
     * @param array $moduleNames
     * @param string $rootPath
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function findModulesByNames(array $moduleNames, string $rootPath): array;
}

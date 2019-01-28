<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;
use PHPMD\AbstractNode;

interface PropelQueryFacadeInterface
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \PHPMD\AbstractNode[]
     */
    public function getRelationTableNames(AbstractNode $node): array;

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getRootApplicationFolderPathByFilePath(string $filePath): string;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return array
     */
    public function getDeclaredDependentModuleNames(AbstractNode $node): array;

    /**
     * @param array $moduleNames
     * @param string $rootPath
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function findModulesByNames(array $moduleNames, string $rootPath): array;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getQueryModuleName(AbstractNode $node): string;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getModuleName(AbstractNode $node): string;

    /**
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer $module
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    public function getTablesByModule(ModuleTransfer $module): array;
}

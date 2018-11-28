<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery;

use ArchitectureSniffer\Module\Transfer\ModuleTransfer;
use PHPMD\AbstractNode;

class PropelQueryFacade implements PropelQueryFacadeInterface
{
    /**
     * @var \ArchitectureSniffer\PropelQuery\PropelQueryFactory
     */
    protected $factory;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \PHPMD\AbstractNode[]
     */
    public function getRelationTableNames(AbstractNode $node): array
    {
        return $this->getFactory()->createMethodNodeReader()->getRelationNames($node);
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getRootApplicationFolderPathByFilePath(string $filePath): string
    {
        return $this->getFactory()
            ->createPathBuilder()
            ->getRootApplicationFolderPathByFilePath($filePath);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string[]
     */
    public function getDeclaredDependentModuleNames(AbstractNode $node): array
    {
        return $this->getFactory()
            ->createDocBlockNodeReader()
            ->getModuleNames($node);
    }

    /**
     * @param array $moduleNames
     * @param string $rootPath
     *
     * @return \ArchitectureSniffer\Module\Transfer\ModuleTransfer[]
     */
    public function findModulesByNames(array $moduleNames, string $rootPath): array
    {
        return $this->getFactory()
            ->createModuleFinder()
            ->findModulesByNames($moduleNames, $rootPath);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getQueryModuleName(AbstractNode $node): string
    {
        return $this->getFactory()->createMethodNodeReader()->getQueryModuleName($node);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getModuleName(AbstractNode $node): string
    {
        return $this->getFactory()->createMethodNodeReader()->getModuleName($node);
    }

    /**
     * @param \ArchitectureSniffer\Module\Transfer\ModuleTransfer $module
     *
     * @return \ArchitectureSniffer\PropelQuery\Schema\Transfer\PropelSchemaTableTransfer[]
     */
    public function getTablesByModule(ModuleTransfer $module): array
    {
        return $this->getFactory()->createPropelSchemaTableFinder()->getTablesByModule($module);
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

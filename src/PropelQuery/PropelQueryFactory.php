<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery;

use ArchitectureSniffer\Module\ModuleFinder;
use ArchitectureSniffer\Module\ModuleFinderInterface;
use ArchitectureSniffer\Node\DocBlock\CustomTags\ModuleTag;
use ArchitectureSniffer\Node\DocBlock\Mapper\DocBlockNodeMapper;
use ArchitectureSniffer\Node\DocBlock\Mapper\DocBlockNodeMapperInterface;
use ArchitectureSniffer\Node\DocBlock\Reader\DocBlockNodeReader;
use ArchitectureSniffer\Node\DocBlock\Reader\DocBlockNodeReaderInterface;
use ArchitectureSniffer\Node\Method\MethodNodeReader;
use ArchitectureSniffer\Node\Method\MethodNodeReaderInterface;
use ArchitectureSniffer\Path\PathBuilder;
use ArchitectureSniffer\Path\PathBuilderInterface;
use ArchitectureSniffer\PropelQuery\Schema\PropelSchemaTableFinder;
use ArchitectureSniffer\PropelQuery\Schema\PropelSchemaTableFinderInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use Symfony\Component\Finder\Finder;
use Zend\Config\Reader\ReaderInterface;
use Zend\Config\Reader\Xml;

class PropelQueryFactory
{
    /**
     * @return \ArchitectureSniffer\Node\DocBlock\Reader\DocBlockNodeReaderInterface
     */
    public function createDocBlockNodeReader(): DocBlockNodeReaderInterface
    {
        return new DocBlockNodeReader(
            $this->createDocBlockFactory(),
            $this->createDocBlockNodeMapper()
        );
    }

    /**
     * @return \ArchitectureSniffer\Node\Method\MethodNodeReaderInterface
     */
    public function createMethodNodeReader(): MethodNodeReaderInterface
    {
        return new MethodNodeReader();
    }

    /**
     * @return \ArchitectureSniffer\Module\ModuleFinder
     */
    public function createModuleFinder(): ModuleFinderInterface
    {
        return new ModuleFinder(
            $this->createPathBuilder()
        );
    }

    /**
     * @return \ArchitectureSniffer\Path\PathBuilderInterface
     */
    public function createPathBuilder(): PathBuilderInterface
    {
        return new PathBuilder();
    }

    /**
     * @return \ArchitectureSniffer\PropelQuery\Schema\PropelSchemaTableFinderInterface
     */
    public function createPropelSchemaTableFinder(): PropelSchemaTableFinderInterface
    {
        return new PropelSchemaTableFinder(
            $this->createConfigReader()
        );
    }

    /**
     * @return \Zend\Config\Reader\ReaderInterface
     */
    protected function createConfigReader(): ReaderInterface
    {
        return new Xml();
    }

    /**
     * @return \ArchitectureSniffer\Node\DocBlock\Mapper\DocBlockNodeMapperInterface
     */
    protected function createDocBlockNodeMapper(): DocBlockNodeMapperInterface
    {
        return new DocBlockNodeMapper();
    }

    /**
     * @return \phpDocumentor\Reflection\DocBlockFactoryInterface
     */
    protected function createDocBlockFactory(): DocBlockFactoryInterface
    {
        return DocBlockFactory::createInstance($this->getDocBlockCustomTags());
    }

    /**
     * @return array
     */
    protected function getDocBlockCustomTags(): array
    {
        return [
            'module' => ModuleTag::class,
        ];
    }
}

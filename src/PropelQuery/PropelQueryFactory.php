<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery;

use ArchitectureSniffer\Module\ModuleFinder;
use ArchitectureSniffer\Module\ModuleFinderInterface;
use ArchitectureSniffer\Node\DocBlock\CustomTags\ModuleTag;
use ArchitectureSniffer\Node\DocBlock\Mapper\NodeDocBlockMapper;
use ArchitectureSniffer\Node\DocBlock\Mapper\NodeDocBlockMapperInterface;
use ArchitectureSniffer\Node\DocBlock\Reader\NodeDocBlockReader;
use ArchitectureSniffer\Node\DocBlock\Reader\NodeDocBlockReaderInterface;
use ArchitectureSniffer\Node\Method\NodeMethodReader;
use ArchitectureSniffer\Node\Method\NodeMethodReaderInterface;
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
     * @return \ArchitectureSniffer\Node\DocBlock\Reader\NodeDocBlockReaderInterface
     */
    public function createDocBlockReader(): NodeDocBlockReaderInterface
    {
        return new NodeDocBlockReader(
            $this->createDocBlockFactory(),
            $this->createDocBlockMapper()
        );
    }

    /**
     * @return \ArchitectureSniffer\Node\Method\NodeMethodReaderInterface
     */
    public function createNodeReader(): NodeMethodReaderInterface
    {
        return new NodeMethodReader();
    }

    /**
     * @return \ArchitectureSniffer\Module\ModuleFinder
     */
    public function createModuleFinder(): ModuleFinderInterface
    {
        return new ModuleFinder(
            $this->createPathBuilder(),
            $this->createSymfonyFinder()
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
            $this->createZendConfigXmlReader()
        );
    }

    /**
     * @return \Zend\Config\Reader\ReaderInterface
     */
    protected function createZendConfigXmlReader(): ReaderInterface
    {
        return new Xml();
    }

    /**
     * @return \ArchitectureSniffer\Node\DocBlock\Mapper\NodeDocBlockMapperInterface
     */
    protected function createDocBlockMapper(): NodeDocBlockMapperInterface
    {
        return new NodeDocBlockMapper();
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

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createSymfonyFinder(): Finder
    {
        return new Finder();
    }
}

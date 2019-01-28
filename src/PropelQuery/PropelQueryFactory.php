<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery;

use ArchitectureSniffer\Module\ModuleFinder as ArchitectureSnifferModuleFinder;
use ArchitectureSniffer\Node\DocBlock\CustomTags\ModuleTag;
use ArchitectureSniffer\Node\DocBlock\Mapper\DocBlockNodeMapper;
use ArchitectureSniffer\Node\DocBlock\Mapper\DocBlockNodeMapperInterface;
use ArchitectureSniffer\Node\DocBlock\Reader\DocBlockNodeReader;
use ArchitectureSniffer\Node\DocBlock\Reader\DocBlockNodeReaderInterface;
use ArchitectureSniffer\Node\Reader\NodeReader;
use ArchitectureSniffer\Node\Reader\NodeReaderInterface;
use ArchitectureSniffer\Path\PathBuilder;
use ArchitectureSniffer\Path\PathBuilderInterface;
use ArchitectureSniffer\PropelQuery\Method\MethodFinder;
use ArchitectureSniffer\PropelQuery\Method\MethodFinderInterface;
use ArchitectureSniffer\PropelQuery\Module\ModuleFinder;
use ArchitectureSniffer\PropelQuery\Module\ModuleFinderInterface;
use ArchitectureSniffer\PropelQuery\Query\QueryFinder;
use ArchitectureSniffer\PropelQuery\Query\QueryFinderInterface;
use ArchitectureSniffer\PropelQuery\Relation\RelationFinder;
use ArchitectureSniffer\PropelQuery\Relation\RelationFinderInterface;
use ArchitectureSniffer\PropelQuery\Schema\PropelSchemaTableFinder;
use ArchitectureSniffer\PropelQuery\Schema\PropelSchemaTableFinderInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\ClassReflector;
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
     * @return \ArchitectureSniffer\Node\Reader\NodeReaderInterface
     */
    public function createNodeReader(): NodeReaderInterface
    {
        return new NodeReader(
            $this->createClassReflector()
        );
    }

    /**
     * @return \ArchitectureSniffer\PropelQuery\Module\ModuleFinderInterface
     */
    public function createModuleFinder(): ModuleFinderInterface
    {
        $architectureSnifferModuleFinder = new ArchitectureSnifferModuleFinder($this->createPathBuilder());

        return new ModuleFinder(
            $architectureSnifferModuleFinder,
            $this->createClassReflector(),
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
            $this->createConfigReader(),
            $this->createPathBuilder(),
            $this->createModuleFinder()
        );
    }

    /**
     * @return \Roave\BetterReflection\Reflector\ClassReflector
     */
    public function createClassReflector(): ClassReflector
    {
        return (new BetterReflection())->classReflector();
    }

    /**
     * @return \ArchitectureSniffer\PropelQuery\Method\MethodFinderInterface
     */
    public function createMethodFinder(): MethodFinderInterface
    {
        return new MethodFinder(
            $this->createRelationFinder(),
            $this->createQueryFinder(),
            $this->createDocBlockNodeReader()
        );
    }

    /**
     * @return \ArchitectureSniffer\PropelQuery\Relation\RelationFinderInterface
     */
    public function createRelationFinder(): RelationFinderInterface
    {
        return new RelationFinder();
    }

    /**
     * @return \ArchitectureSniffer\PropelQuery\Query\QueryFinderInterface
     */
    public function createQueryFinder(): QueryFinderInterface
    {
        return new QueryFinder();
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

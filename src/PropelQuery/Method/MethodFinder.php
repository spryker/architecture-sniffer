<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery\Method;

use ArchitectureSniffer\Node\DocBlock\Reader\DocBlockNodeReaderInterface;
use ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer;
use ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer;
use ArchitectureSniffer\PropelQuery\Query\QueryFinderInterface;
use ArchitectureSniffer\PropelQuery\Relation\RelationFinderInterface;
use PHPMD\Node\MethodNode;

class MethodFinder implements MethodFinderInterface
{
    /**
     * @var \ArchitectureSniffer\PropelQuery\Relation\RelationFinderInterface
     */
    protected $relationFinder;

    /**
     * @var \ArchitectureSniffer\PropelQuery\Query\QueryFinderInterface
     */
    protected $queryFinder;

    /**
     * @var \ArchitectureSniffer\Node\DocBlock\Reader\DocBlockNodeReaderInterface
     */
    protected $docBlockNodeReader;

    /**
     * @param \ArchitectureSniffer\PropelQuery\Relation\RelationFinderInterface $relationFinder
     * @param \ArchitectureSniffer\PropelQuery\Query\QueryFinderInterface $queryFinder
     * @param \ArchitectureSniffer\Node\DocBlock\Reader\DocBlockNodeReaderInterface $docBlockNodeReader
     */
    public function __construct(
        RelationFinderInterface $relationFinder,
        QueryFinderInterface $queryFinder,
        DocBlockNodeReaderInterface $docBlockNodeReader
    ) {
        $this->relationFinder = $relationFinder;
        $this->queryFinder = $queryFinder;
        $this->docBlockNodeReader = $docBlockNodeReader;
    }

    /**
     * @param \ArchitectureSniffer\PropelQuery\ClassNode\Transfer\ClassNodeTransfer $classNodeTransfer
     *
     * @return array<\ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer>
     */
    public function getMethodTransferCollectionWithRelations(ClassNodeTransfer $classNodeTransfer): array
    {
        $methodTransferCollection = [];

        $className = $classNodeTransfer->getClassName();
        $classMethods = $classNodeTransfer->getNode()->getMethods();
        $reflectionClass = $classNodeTransfer->getReflectionClass();

        foreach ($classMethods as $methodNode) {
            $relationTablesNames = $this->relationFinder->getRelationNames(
                $methodNode,
                $reflectionClass,
            );

            if ($relationTablesNames === []) {
                continue;
            }

            $queryNames = $this->queryFinder->getQueryNames(
                $methodNode,
                $reflectionClass,
            );

            $methodName = $methodNode->getName();

            $methodTransferCollection[$methodName] = $this->createMethodTransfer(
                $className,
                $methodNode,
                $queryNames,
                $relationTablesNames,
            );
        }

        return $methodTransferCollection;
    }

    /**
     * @param string $className
     * @param \PHPMD\Node\MethodNode $methodNode
     * @param array<string> $queryNames
     * @param array<string> $relationTablesNames
     *
     * @return \ArchitectureSniffer\PropelQuery\Method\Transfer\MethodTransfer
     */
    protected function createMethodTransfer(
        string $className,
        MethodNode $methodNode,
        array $queryNames,
        array $relationTablesNames
    ): MethodTransfer {
        $methodTransfer = new MethodTransfer();

        $methodTransfer->setClassName($className);
        $methodTransfer->setNamespace($methodNode->getFullQualifiedName());
        $methodTransfer->setMethodName($methodNode->getName());
        $methodTransfer->setQueryNames($queryNames);
        $methodTransfer->setRelationNames($relationTablesNames);
        $methodTransfer->setDeclaredDependentModuleNames($this->docBlockNodeReader->getModuleNames($methodNode));

        return $methodTransfer;
    }
}

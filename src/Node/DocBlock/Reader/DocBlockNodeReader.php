<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Node\DocBlock\Reader;

use ArchitectureSniffer\Node\DocBlock\Mapper\DocBlockNodeMapperInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PHPMD\AbstractNode;

class DocBlockNodeReader implements DocBlockNodeReaderInterface
{
    /**
     * @var string
     */
    protected const TAG_MODULE = 'module';

    /**
     * @var \phpDocumentor\Reflection\DocBlockFactoryInterface
     */
    protected $docBlockFactory;

    /**
     * @var \ArchitectureSniffer\Node\DocBlock\Mapper\DocBlockNodeMapperInterface
     */
    protected $docBlockMapper;

    /**
     * @param \phpDocumentor\Reflection\DocBlockFactoryInterface $docBlockFactory
     * @param \ArchitectureSniffer\Node\DocBlock\Mapper\DocBlockNodeMapperInterface $docBlockMapper
     */
    public function __construct(DocBlockFactoryInterface $docBlockFactory, DocBlockNodeMapperInterface $docBlockMapper)
    {
        $this->docBlockFactory = $docBlockFactory;
        $this->docBlockMapper = $docBlockMapper;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return array<string>
     */
    public function getModuleNames(AbstractNode $node): array
    {
        $docBlock = $this->getDocBlock($node);

        if ($docBlock === null) {
            return [];
        }

        $moduleTags = $docBlock->getTagsByName(static::TAG_MODULE);
        $moduleTags = $this->docBlockMapper->tagsToArray($moduleTags);

        return $moduleTags[static::TAG_MODULE] ?? [];
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \phpDocumentor\Reflection\DocBlock|null
     */
    protected function getDocBlock(AbstractNode $node): ?DocBlock
    {
        $comment = $node->getComment();

        if ($comment === null) {
            return null;
        }

        return $this->docBlockFactory->create($comment);
    }
}

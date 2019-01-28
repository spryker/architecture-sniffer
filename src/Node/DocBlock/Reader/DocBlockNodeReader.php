<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\DocBlock\Reader;

use ArchitectureSniffer\Node\DocBlock\Mapper\DocBlockNodeMapperInterface;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PHPMD\AbstractNode;

class DocBlockNodeReader implements DocBlockNodeReaderInterface
{
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
     * @return string[]
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

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\DocBlock\Mapper;

interface DocBlockNodeMapperInterface
{
    /**
     * @param \phpDocumentor\Reflection\DocBlock\Tags\BaseTag[] $tags
     *
     * @return array
     */
    public function tagsToArray(array $tags): array;
}

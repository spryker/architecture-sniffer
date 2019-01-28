<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\DocBlock\Mapper;

class DocBlockNodeMapper implements DocBlockNodeMapperInterface
{
    /**
     * @param \phpDocumentor\Reflection\DocBlock\Tags\BaseTag[] $tags
     *
     * @return array
     */
    public function tagsToArray(array $tags): array
    {
        $result = [];

        foreach ($tags as $tag) {
            $tagType = $tag->getName();

            $result[$tagType][] = $tag->getDescription()->render();
        }

        return $result;
    }
}

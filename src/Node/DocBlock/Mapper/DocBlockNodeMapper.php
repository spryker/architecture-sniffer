<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

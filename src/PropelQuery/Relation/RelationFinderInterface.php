<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Relation;

use PHPMD\AbstractNode;
use Roave\BetterReflection\Reflection\ReflectionClass;

interface RelationFinderInterface
{
    /**
     * @param \PHPMD\AbstractNode $node
     * @param \Roave\BetterReflection\Reflection\ReflectionClass $reflectionNodeClass
     *
     * @return array
     */
    public function getRelationNames(AbstractNode $node, ReflectionClass $reflectionNodeClass): array;
}

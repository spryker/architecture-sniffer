<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Query;

use PHPMD\Node\AbstractNode;
use Roave\BetterReflection\Reflection\ReflectionClass;

interface QueryFinderInterface
{
    /**
     * @param \PHPMD\Node\AbstractNode $node
     * @param \Roave\BetterReflection\Reflection\ReflectionClass $reflectionNodeClass
     *
     * @return string[]
     */
    public function getQueryNames(AbstractNode $node, ReflectionClass $reflectionNodeClass): array;
}

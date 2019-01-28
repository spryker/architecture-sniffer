<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @return string[]
     */
    public function getRelationNames(AbstractNode $node, ReflectionClass $reflectionNodeClass): array;
}

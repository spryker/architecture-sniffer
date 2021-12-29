<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery\Relation;

use PHPMD\AbstractNode;
use PHPStan\BetterReflection\Reflection\ReflectionClass;

interface RelationFinderInterface
{
    /**
     * @param \PHPMD\AbstractNode $node
     * @param \PHPStan\BetterReflection\Reflection\ReflectionClass $reflectionNodeClass
     *
     * @return array<string>
     */
    public function getRelationNames(AbstractNode $node, ReflectionClass $reflectionNodeClass): array;
}

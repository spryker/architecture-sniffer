<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery\Query;

use PHPMD\Node\AbstractNode;
use PHPStan\BetterReflection\Reflection\ReflectionClass;

interface QueryFinderInterface
{
    /**
     * @param \PHPMD\Node\AbstractNode $node
     * @param \PHPStan\BetterReflection\Reflection\ReflectionClass $reflectionNodeClass
     *
     * @return string[]
     */
    public function getQueryNames(AbstractNode $node, ReflectionClass $reflectionNodeClass): array;
}

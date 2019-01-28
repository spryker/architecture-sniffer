<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\DocBlock\Reader;

use PHPMD\AbstractNode;

interface DocBlockNodeReaderInterface
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return array
     */
    public function getModuleNames(AbstractNode $node): array;
}

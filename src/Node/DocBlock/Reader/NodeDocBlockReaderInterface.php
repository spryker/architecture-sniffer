<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\DocBlock\Reader;

use PHPMD\AbstractNode;

interface NodeDocBlockReaderInterface
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return array
     */
    public function getModules(AbstractNode $node): array;
}

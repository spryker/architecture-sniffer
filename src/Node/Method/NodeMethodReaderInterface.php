<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\Method;

use PHPMD\AbstractNode;

interface NodeMethodReaderInterface
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \PHPMD\AbstractNode[]
     */
    public function getJoinNames(AbstractNode $node): array;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getModuleName(AbstractNode $node): string;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getQueryModuleName(AbstractNode $node): string;
}

<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\Reader;

use PHPMD\AbstractNode;
use Roave\BetterReflection\Reflection\ReflectionClass;

interface NodeReaderInterface
{
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
    public function getClassName(AbstractNode $node): string;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \Roave\BetterReflection\Reflection\ReflectionClass
     */
    public function getReflectionClassByNode(AbstractNode $node): ReflectionClass;
}

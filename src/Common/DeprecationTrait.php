<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common;

use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;

trait DeprecationTrait
{
    /**
     * @var string
     */
    protected $regexp = '/@deprecated/i';

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function isMethodDeprecated(MethodNode $method)
    {
        return (bool)preg_match($this->regexp, $method->getNode()->getDocComment());
    }

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return bool
     */
    protected function isClassDeprecated(ClassNode $classNode)
    {
        return (bool)preg_match($this->regexp, $classNode->getNode()->getDocComment());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
        $comment = $method->getNode()->getComment();

        if ($comment === null) {
            return false;
        }

        return (bool)preg_match($this->regexp, $comment);
    }

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return bool
     */
    protected function isClassDeprecated(ClassNode $classNode)
    {
        $comment = $classNode->getNode()->getComment();

        if ($comment === null) {
            return false;
        }

        return (bool)preg_match($this->regexp, $classNode->getNode()->getComment());
    }
}

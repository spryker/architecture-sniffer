<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;

trait DeprecationTrait
{
    /**
     * @var string
     */
    protected $deprecatedDockBlock = '@deprecated';

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function isMethodDeprecated(MethodNode $method)
    {
        return $this->isNodeDeprecated($method);
    }

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return bool
     */
    protected function isClassDeprecated(ClassNode $classNode)
    {
        return $this->isNodeDeprecated($classNode);
    }

    /**
     * @param $node
     *
     * @return bool
     */
    protected function isNodeDeprecated(AbstractNode $node): bool
    {
        $comment = $node->getNode()->getComment();

        if ($comment === null) {
            return false;
        }

        return stripos($comment, $this->deprecatedDockBlock) !== false;
    }
}

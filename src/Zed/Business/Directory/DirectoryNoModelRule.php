<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Business\Directory;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

class DirectoryNoModelRule extends AbstractDirectoryRule implements ClassAware
{
    public const RULE = 'Business models must not be in a Model directory.';

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::RULE;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (!$this->isInBusinessLayer($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\ClassNode $classNode
     *
     * @return void
     */
    protected function applyRule(ClassNode $classNode)
    {
        if (!preg_match('/Business\\\\Model\\\\/', $classNode->getFullQualifiedName())) {
            return;
        }

        $message = sprintf(
            'The %s is inside a Model directory which violates rule "%s"',
            $classNode->getFullQualifiedName(),
            static::RULE
        );

        $this->addViolation($classNode, [$message]);
    }
}

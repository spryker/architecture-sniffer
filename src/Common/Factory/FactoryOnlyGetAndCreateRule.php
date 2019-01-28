<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class FactoryOnlyGetAndCreateRule extends AbstractFactoryRule implements ClassAware
{
    public const RULE = 'Factories should only contain get*() and create*() methods.';

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
        if (!$this->isFactory($node)) {
            return;
        }

        foreach ($node->getMethods() as $method) {
            $this->applyRule($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
    {
        if (preg_match('/^(\_\_|create|get).+/', $method->getName()) !== 0) {
            return;
        }

        $message = sprintf(
            'The factory method %s() violates rule "%s"',
            $method->getName(),
            static::RULE
        );

        $this->addViolation($method, [$message]);
    }
}

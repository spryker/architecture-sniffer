<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FactoryOnlyPublicMethodsRule extends AbstractFactoryRule implements MethodAware
{
    /**
     * @var string
     */
    public const RULE = 'All the factory methods should be public by default';

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

        if ($this->isMethodDeprecated($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $node
     *
     * @return void
     */
    protected function applyRule(MethodNode $node): void
    {
        if ($node->isPublic()) {
            return;
        }

        $method = $node->getName();

        $message = sprintf(
            'The factory method \'%s()\' is not public which violates the rule "%s"',
            $method,
            static::RULE,
        );

        $this->addViolation($node, [$message]);
    }
}

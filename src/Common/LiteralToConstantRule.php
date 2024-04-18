<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * literals in the codebase must be avoided.
 */
class LiteralToConstantRule extends AbstractRule implements MethodAware
{
    /**
     * @var string
     */
    protected const RULE = 'Avoid using literals, use constants instead.';

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
        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
    {
        foreach ($method->findChildrenOfType('Literal') as $literal) {
            $message = sprintf(
                'The method `%s` contains a literal which violates the rule "%s"',
                $method->getFullQualifiedName(),
                static::RULE,
            );

            $this->addViolation($method, [$message]);
        }
    }
}

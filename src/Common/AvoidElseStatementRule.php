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
 * `else` and `elseif` statements must be avoided.
 */
class AvoidElseStatementRule extends AbstractRule implements MethodAware
{
    /**
     * @var string
     */
    protected const RULE = 'Avoid using `else` and `elseif` statements, use early returns instead.';

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
        foreach ($method->findChildrenOfType('IfStatement') as $ifStatement) {
            if ($ifStatement->hasElse()) {
                $message = sprintf(
                    'The method `%s` contains an `else` statement which violates the rule "%s"',
                    $method->getFullQualifiedName(),
                    static::RULE,
                );

                $this->addViolation($method, [$message]);
            }
        }

        foreach ($method->findChildrenOfType('ElseIfStatement') as $elseIfStatement) {
            $message = sprintf(
                'The method `%s` contains an `elseif` statement which violates the rule "%s"',
                $method->getFullQualifiedName(),
                static::RULE,
            );

            $this->addViolation($method, [$message]);
        }
    }
}

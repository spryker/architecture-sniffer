<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FactoryGetContainNoNewRule extends AbstractFactoryRule implements MethodAware
{
    public const RULE = 'A `get*()` method in factories must not contain a `new` keyword.';

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
        if (!$this->isApplicable($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isApplicable(AbstractNode $node): bool
    {
        if (!$this->isFactory($node) || $this->isAbstractFactory($node)) {
            return false;
        }

        if ($this->isMethodDeprecated($node)) {
            return false;
        }

        return true;
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
    {
        if (substr($method->getName(), 0, 3) !== 'get') {
            return;
        }

        $count = count($method->findChildrenOfType('AllocationExpression'));
        if ($count === 0) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The factory method %s contains %d new statements which violates rule "' . static::RULE . '"',
                    $method->getFullQualifiedName(),
                    $count
                ),
            ]
        );
    }
}

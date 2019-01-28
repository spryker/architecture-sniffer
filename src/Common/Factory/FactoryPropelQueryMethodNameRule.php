<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FactoryPropelQueryMethodNameRule extends AbstractFactoryRule implements MethodAware
{
    public const RULE = 'Get propel query methods must be named like get*PropelQuery() in factory.';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
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
        $constant = $node->getFirstChildOfType('ConstantPostfix');

        if ($constant === null) {
            return;
        }

        if (!$this->isPropelQueryConstant($constant->getName())) {
            return;
        }

        $methodName = $node->getName();

        if ($this->isPropelQueryFactoryMethod($methodName)) {
            return;
        }

        $class = $node->getParentName();

        $message = sprintf(
            '%s violates rule "%s"',
            "{$class}::{$methodName}()",
            static::RULE
        );

        $this->addViolation($node, [$message]);
    }

    /**
     * @param string $constantName
     *
     * @return bool
     */
    protected function isPropelQueryConstant(string $constantName): bool
    {
        $propelQueryConstantPattern = '/^PROPEL_QUERY_.+/';

        if (!preg_match($propelQueryConstantPattern, $constantName)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    protected function isPropelQueryFactoryMethod(string $methodName): bool
    {
        $propelQueryFactoryMethodPattern = '/^get([a-zA-Z]+)PropelQuery$/';

        if (!preg_match($propelQueryFactoryMethodPattern, $methodName)) {
            return false;
        }

        return true;
    }
}

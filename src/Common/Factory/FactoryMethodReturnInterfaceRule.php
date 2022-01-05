<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FactoryMethodReturnInterfaceRule extends AbstractFactoryRule implements MethodAware
{
    /**
     * @var string
     */
    public const RULE = 'Every method in a Factory must only return an interface or an array of interfaces.';

    /**
     * @var string
     */
    protected const ALLOWED_RETURN_TYPES_PATTERN = '/@return\s(?!((.*)Interface|(.*)DataProvider|callable))(.*)/';

    /**
     * @var int
     */
    protected const INVALID_RETURN_TYPE_MATCH = 3;

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
    protected function applyRule(MethodNode $node)
    {
        $comment = $node->getComment();
        if ($this->hasInvalidReturnType($comment)) {
            $class = $node->getParentName();
            $method = $node->getName();
            $fullClassName = $node->getFullQualifiedName();

            $message = sprintf(
                '`%s` (`%s`) returns a concrete class which violates the rule "%s"',
                "{$class}::{$method}()",
                $fullClassName,
                static::RULE,
            );

            $this->addViolation($node, [$message]);
        }
    }

    /**
     * @param string $comment
     *
     * @return bool
     */
    protected function hasInvalidReturnType($comment)
    {
        if (preg_match(self::ALLOWED_RETURN_TYPES_PATTERN, $comment)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $comment
     *
     * @return bool
     */
    protected function getInvalidReturnType($comment)
    {
        if (preg_match(self::ALLOWED_RETURN_TYPES_PATTERN, $comment, $returnType)) {
            return $returnType[self::INVALID_RETURN_TYPE_MATCH];
        }

        return false;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Method;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ExistsPostfixRule extends AbstractRule implements ClassAware
{
    /**
     * @var string
     */
    protected const POSTFIX_PATTERN = '/Exists?([A-Z]|$)/';

    /**
     * @var string
     */
    protected const POSTFIX_EXISTS = 'Exists';

    /**
     * @var string
     */
    protected const POSTFIX_EXIST = 'Exist';

    /**
     * @var string
     */
    protected const POSTFIX_EXISTING = 'Existing';

    /**
     * @var string
     */
    protected const PREFIX_METHOD_NAME = 'is';

    /**
     * @var string
     */
    protected const RULE_EXISTS_INSTEAD_OF_EXIST = 'Postfix `Exists` must be used instead of `Exist`. Method: %s.';

    /**
     * @var string
     */
    protected const RULE_EXISTS_IN_THE_END = 'Postfix `Exists` must be in the end of method name. Method: %s.';

    /**
     * @var string
     */
    protected const RULE_FORBIDDEN_PREFIX_IS = 'Prefix `is` must not be used with postfix `Exist`. Method: %s.';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (!$node instanceof ClassNode) {
            return;
        }

        foreach ($node->getMethods() as $methodNode) {
            $this->verifyMethod($methodNode);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return void
     */
    protected function verifyMethod(MethodNode $methodNode): void
    {
        $methodName = $methodNode->getName();

        if (
            strpos($methodName, static::PREFIX_METHOD_NAME) !== 0
            || !preg_match(static::POSTFIX_PATTERN, $methodName)
        ) {
            return;
        }

        $this->addForbiddenPrefixViolation($methodNode);

        if (strpos($methodName, static::POSTFIX_EXISTS) === false) {
            $this->addPostfixSpellingViolation($methodNode);
        }

        $trimmedMethodName = rtrim($methodName, 's');
        if (strpos($trimmedMethodName, static::POSTFIX_EXIST) !== strlen($trimmedMethodName) - strlen(static::POSTFIX_EXIST)) {
            $this->addPostfixInEndViolation($methodNode);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return void
     */
    protected function addPostfixSpellingViolation(MethodNode $methodNode): void
    {
        $message = sprintf(
            static::RULE_EXISTS_INSTEAD_OF_EXIST,
            $methodNode->getFullQualifiedName(),
        );

        $this->addViolation($methodNode, [$message]);
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return void
     */
    protected function addPostfixInEndViolation(MethodNode $methodNode): void
    {
        $message = sprintf(
            static::RULE_EXISTS_IN_THE_END,
            $methodNode->getFullQualifiedName(),
        );

        $this->addViolation($methodNode, [$message]);
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return void
     */
    protected function addForbiddenPrefixViolation(MethodNode $methodNode): void
    {
        $message = sprintf(
            static::RULE_FORBIDDEN_PREFIX_IS,
            $methodNode->getFullQualifiedName(),
        );

        $this->addViolation($methodNode, [$message]);
    }
}

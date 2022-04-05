<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Method;

use ArchitectureSniffer\Common\DeprecationTrait;
use PDepend\Source\AST\ASTParameter;
use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ASTNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class DeprecatedObjectUsageJsonDecodeRule extends AbstractRule implements ClassAware
{
    use DeprecationTrait;

    /**
     * @var string
     */
    protected const METHOD_NAME = 'decodeJson';

    /**
     * @var string
     */
    protected const CORRECT_VALUE = 'true';

    /**
     * @var string
     */
    protected const RULE_DEPRECATED_USAGE_FOUND = 'Param #2 `$assoc` of `decodeJson` must be `true` as return of type `object` is not accepted. Method: %s.';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (
            !$node instanceof ClassNode
            || $this->isClassDeprecated($node)
        ) {
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
        if ($this->isMethodDeprecated($methodNode)) {
            return;
        }

        $childNodes = $methodNode->findChildrenOfType('MethodPostfix');

        foreach ($childNodes as $childNode) {
            if ($childNode->getName() !== static::METHOD_NAME) {
                continue;
            }

            $this->verifyUsages($childNode, $methodNode);
        }
    }

    /**
     * @param \PHPMD\Node\ASTNode $node
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return void
     */
    protected function verifyUsages(ASTNode $node, MethodNode $methodNode): void
    {
        $arguments = $node->getFirstChildOfType('Arguments')->findChildrenOfType('Literal');
        if ($arguments === []) {
            $this->addRuleViolation($methodNode);

            return;
        }

        foreach ($arguments as $argument) {
            if ($argument->getName() == static::CORRECT_VALUE) {
                continue;
            }

            $this->addRuleViolation($methodNode);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return void
     */
    protected function addRuleViolation(MethodNode $methodNode): void
    {
        $message = sprintf(
            static::RULE_DEPRECATED_USAGE_FOUND,
            $methodNode->getFullQualifiedName(),
        );

        $this->addViolation($methodNode, [$message]);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Method;

use ArchitectureSniffer\Common\DeprecationTrait;
use ArchitectureSniffer\Common\PhpDocTrait;
use PDepend\Source\AST\ASTCallable;
use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class GetterReturnTypeRule extends AbstractRule implements ClassAware
{
    use DeprecationTrait;
    use PhpDocTrait;

    /**
     * @var string
     */
    protected const PREFIX_METHOD_NAME = 'get';

    /**
     * @var string
     */
    protected const EXCEPTIONAL_POSTFIX_STATUS = 'Status';

    /**
     * @var string
     */
    protected const RULE_RETURN_TYPE_SPECIFIED = 'Getter `%s` must return something. Please add return type.';

    /**
     * @var string
     */
    protected const RULE_RETURN_NOT_BOOL = 'Boolean methods do not use `get` prefix, use `is`/`has` instead or alike. Getter `%s` must not return bool.';

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
        $methodName = $methodNode->getName();

        if (
            strpos($methodName, static::PREFIX_METHOD_NAME) !== 0
            || !$this->apiTagExists($methodNode->getNode()->getComment())
            || $this->isMethodDeprecated($methodNode)
            || strpos($methodName, static::EXCEPTIONAL_POSTFIX_STATUS) === strlen($methodName) - strlen(static::EXCEPTIONAL_POSTFIX_STATUS)
        ) {
            return;
        }

        $returnType = $this->getReturnType($methodNode);

        if (
            $returnType === null && !$this->inheritDocTagExists($methodNode->getNode()->getComment())
            || $returnType === 'void'
        ) {
            $this->addMethodMustReturnViolation($methodNode);

            return;
        }

        if ($returnType === 'bool') {
            $this->addMethodReturnBoolViolation($methodNode);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return string|null
     */
    protected function getReturnType(MethodNode $methodNode): ?string
    {
        $artifact = $methodNode->getNode();

        if (!$artifact instanceof ASTCallable) {
            return null;
        }

        /** @var \PDepend\Source\AST\ASTType|null $returnType */
        $returnType = $artifact->getReturnType();

        if ($returnType !== null) {
            return $returnType->getImage();
        }

        $phpDoc = $artifact->getComment();

        if ($phpDoc === null) {
            return null;
        }

        return $this->getReturnTypeByPhpDoc($phpDoc);
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return void
     */
    protected function addMethodReturnBoolViolation(MethodNode $methodNode): void
    {
        $message = sprintf(
            static::RULE_RETURN_NOT_BOOL,
            $methodNode->getFullQualifiedName(),
        );

        $this->addViolation($methodNode, [$message]);
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return void
     */
    protected function addMethodMustReturnViolation(MethodNode $methodNode): void
    {
        $message = sprintf(
            static::RULE_RETURN_TYPE_SPECIFIED,
            $methodNode->getFullQualifiedName(),
        );

        $this->addViolation($methodNode, [$message]);
    }
}

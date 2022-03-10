<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Factory;

use ArchitectureSniffer\Common\PhpDocTrait;
use PDepend\Source\AST\ASTClassOrInterfaceReference;
use PDepend\Source\AST\ASTMethodPostfix;
use PDepend\Source\AST\ASTParentReference;
use PDepend\Source\AST\ASTSelfReference;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * Every Factory method named create* should only have a single "new ..." instantiation.
 */
class FactoryCreateContainOneNewRule extends AbstractFactoryRule implements MethodAware
{
    use PhpDocTrait;

    /**
     * @var string
     */
    protected const PATTERN_QUERY_CLASS_NAME = '#Orm\\\\Zed\\\\[A-Za-z]+\\\\Persistence\\\\[A-Za-z]+Query#';

    /**
     * @var string
     */
    protected const RULE = 'A `create*()` method in factories must contain exactly 1 `new` statement for instantiation.';

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
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function addCreationPropelQueryViolation(MethodNode $method): void
    {
        $message = sprintf(
            'A `create*Query()` method must look like `return Spy*Query::create();`. `%s` violates this rule.',
            $method->getFullQualifiedName(),
        );

        $this->addViolation($method, [$message]);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
    {
        if (!$this->isMethodNameStartsWithCreate($method->getName())) {
            return;
        }

        $isQueryCreationMethod = $this->isCreationPropelQueryMethod($method);

        $count = count($method->findChildrenOfType('AllocationExpression'));

        if ($count === 1) {
            if ($isQueryCreationMethod) {
                $this->addCreationPropelQueryViolation($method);
            }

            return;
        }

        if ($this->isParentCall($method)) {
            return;
        }

        if ($this->isIndirectFactoryMethod($method)) {
            return;
        }

        $throwStatements = $method->findChildrenOfType('ThrowStatement');
        if ($count - count($throwStatements) === 1) {
            return;
        }

        $methodName = $method->getParentName() . '::' . $method->getName() . '()';
        $className = $method->getFullQualifiedName();

        if ($isQueryCreationMethod) {
            $this->addCreationPropelQueryViolation($method);

            return;
        }

        $message = sprintf(
            '`%s` in `%s` contains %s new statements which violates rule "%s"',
            $methodName,
            $className,
            $count,
            static::RULE,
        );

        $this->addViolation($method, [$message]);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function isParentCall(MethodNode $method)
    {
        $primaryPrefixes = $method->findChildrenOfType('MemberPrimaryPrefix');
        if (count($primaryPrefixes) < 1) {
            return false;
        }

        $firstPrimaryPrefix = $primaryPrefixes[0];

        return $firstPrimaryPrefix->getChild(0)->getName() === 'parent';
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function isIndirectFactoryMethod(MethodNode $method)
    {
        $primaryPrefixes = $method->findChildrenOfType('MemberPrimaryPrefix');
        if (count($primaryPrefixes) < 1) {
            return false;
        }

        $firstPrimaryPrefix = $primaryPrefixes[0];

        if ($firstPrimaryPrefix->getChild(0)->getName() === '$this' && $this->isMethodNameStartsWithCreate($firstPrimaryPrefix->getChild(1)->getName())) {
            return true;
        }

        if ($firstPrimaryPrefix->getChild(0)->getName() === '$this' && substr($firstPrimaryPrefix->getChild(1)->getName(), 0, 2) === '->') {
            if ($this->isMethodNameStartsWithCreate($primaryPrefixes[1]->getChild(0)->getName())) {
                return true;
            }
        }

        if (
            $firstPrimaryPrefix->getParent()->getName() === 'return' &&
            $this->isMethodNameStartsWithCreate($firstPrimaryPrefix->getChild(1)->getNode()->getImage()) &&
            $this->isStaticMethodCall($firstPrimaryPrefix)
        ) {
            return true;
        }

        foreach ($primaryPrefixes as $primaryPrefix) {
            if ($primaryPrefix->getParent()->getName() === 'return') {
                $returnNodeChildren = $primaryPrefix->findChildrenOfType('MethodPostfix');

                foreach ($returnNodeChildren as $returnNodeChild) {
                    if ($this->isMethodNameStartsWithCreate($returnNodeChild->getChild(0)->getName())) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param \PHPMD\AbstractNode $methodCall
     *
     * @return bool
     */
    protected function isStaticMethodCall(AbstractNode $methodCall): bool
    {
        return $methodCall->getChild(0)->getNode() instanceof ASTClassOrInterfaceReference &&
            $methodCall->getChild(1)->getNode() instanceof ASTMethodPostfix &&
            !$this->isCallingParent($methodCall) &&
            !$this->isCallingSelf($methodCall);
    }

    /**
     * @param \PHPMD\AbstractNode $methodCall
     *
     * @return bool
     */
    protected function isCallingParent(AbstractNode $methodCall): bool
    {
        return $methodCall->getChild(0)->getNode() instanceof ASTParentReference;
    }

    /**
     * @param \PHPMD\AbstractNode $methodCall
     *
     * @return bool
     */
    protected function isCallingSelf(AbstractNode $methodCall): bool
    {
        return $methodCall->getChild(0)->getNode() instanceof ASTSelfReference;
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    protected function isMethodNameStartsWithCreate(string $methodName): bool
    {
        return substr($methodName, 0, 6) === 'create';
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return bool
     */
    protected function isCreationPropelQueryMethod(MethodNode $methodNode): bool
    {
        return $this->isMethodNameEndsWithQuery($methodNode->getName())
            && $this->isMethodReturnsSpyQuery($methodNode);
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    protected function isMethodNameEndsWithQuery(string $methodName): bool
    {
        return substr($methodName, -5) === 'Query';
    }

    /**
     * @param \PHPMD\Node\MethodNode $methodNode
     *
     * @return bool
     */
    protected function isMethodReturnsSpyQuery(MethodNode $methodNode): bool
    {
        $returnType = $this->getReturnTypeByPhpDoc($methodNode->getNode()->getComment());

        if (!$returnType) {
            return false;
        }

        return $this->isQueryClass($returnType);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function isQueryClass(string $className): bool
    {
        return preg_match(static::PATTERN_QUERY_CLASS_NAME, $className);
    }
}

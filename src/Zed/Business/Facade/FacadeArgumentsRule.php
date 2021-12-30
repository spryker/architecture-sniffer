<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Business\Facade;

use PDepend\Source\AST\ASTParameter;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FacadeArgumentsRule extends AbstractFacadeRule implements MethodAware
{
    /**
     * @var string
     */
    public const RULE = 'Every Facade should only retrieve native types or transfer objects.';

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
        if (!$this->isFacade($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
    {
        $params = $method->getParameters();
        foreach ($params as $param) {
            $this->checkParameter($param, $method);
        }
    }

    /**
     * @param \PDepend\Source\AST\ASTParameter $param
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    protected function checkParameter(ASTParameter $param, AbstractNode $node)
    {
        $class = $param->getClass();
        if (empty($class) || $class->getNamespaceName() === 'Generated\Shared\Transfer') {
            return;
        }

        $message = sprintf(
            'The %s is using an invalid argument type which violates the rule "%s"',
            $node->getFullQualifiedName(),
            static::RULE,
        );

        $this->addViolation($node, [$message]);
    }
}

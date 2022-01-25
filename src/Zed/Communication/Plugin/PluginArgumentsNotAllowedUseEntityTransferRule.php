<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Communication\Plugin;

use ArchitectureSniffer\Common\DeprecationTrait;
use ArchitectureSniffer\Common\Plugin\AbstractPluginRule;
use PDepend\Source\AST\AbstractASTClassOrInterface;
use PDepend\Source\AST\ASTParameter;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class PluginArgumentsNotAllowedUseEntityTransferRule extends AbstractPluginRule implements ClassAware
{
    use DeprecationTrait;

    /**
     * @var string
     */
    public const RULE = 'Plugin methods shouldn`t use entity transfers.';

    /**
     * @var string
     */
    protected const NAMESPACE_TRANSFERS = 'Generated\Shared\Transfer';

    /**
     * @var string
     */
    protected const PATTERN_SUFFIX_ENTITY_TRANSFER = '/EntityTransfer?/';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return static::RULE;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (!$this->isPlugin($node) || $this->isClassDeprecated($node)) {
            return;
        }

        $pluginMethods = $node->getMethods();

        foreach ($pluginMethods as $pluginMethod) {
            $this->applyRule($pluginMethod);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method): void
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
    protected function checkParameter(ASTParameter $param, AbstractNode $node): void
    {
        $class = $param->getClass();

        if ($class === null || !$this->isArgumentEntityTransfer($class)) {
            return;
        }

        $message = sprintf(
            'The plugin class method `%s()` is using an invalid argument `%s` type which violates the rule "%s"',
            $node->getFullQualifiedName(),
            $class->getName(),
            static::RULE,
        );

        $this->addViolation($node, [$message]);
    }

    /**
     * @param \PDepend\Source\AST\AbstractASTClassOrInterface $class
     *
     * @return bool
     */
    protected function isArgumentEntityTransfer(AbstractASTClassOrInterface $class): bool
    {
        if ($class->getNamespaceName() !== static::NAMESPACE_TRANSFERS) {
            return false;
        }

        if (!preg_match(static::PATTERN_SUFFIX_ENTITY_TRANSFER, $class->getName())) {
            return false;
        }

        return true;
    }
}

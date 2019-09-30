<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ImplementsApiInheritDocRule extends SprykerAbstractRule implements ClassAware
{
    public const RULE = 'Every API method must also contain the @inheritDoc tag in docblock.';

    /**
     * @var array
     */
    protected $apiClasses = ['Facade', 'QueryContainer', 'Client', 'Service', 'Plugin'];

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
        $nodeNamespace = $node->getNamespaceName();
        if (strpos($nodeNamespace, 'Dependency\\') !== false) {
            return;
        }

        $nodeClassName = $node->getName();
        if (!preg_match('/(' . implode('|', $this->apiClasses) . ')$/', $nodeClassName)) {
            return;
        }

        /** @var \PHPMD\Node\InterfaceNode $node */
        foreach ($node->getMethods() as $method) {
            $this->applyOnMethod($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode|\PDepend\Source\AST\ASTMethod $method
     *
     * @return void
     */
    protected function applyOnMethod(MethodNode $method)
    {
        $comment = $method->getComment();
        if (preg_match(
            '/\{\@inheritDoc\}/',
            $comment
        )) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The interface method %s does not contain an @api tag ' .
                    'which violates rule: "%s"',
                    $method->getFullQualifiedName(),
                    static::RULE
                ),
            ]
        );
    }
}

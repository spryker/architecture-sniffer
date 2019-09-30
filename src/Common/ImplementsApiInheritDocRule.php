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
    protected const RULE = 'Every API public method must also contain the {@inheritDoc} tag in docblock.';

    /**
     * @var string[]
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
     * @param \PHPMD\AbstractNode $classNode
     *
     * @return void
     */
    public function apply(AbstractNode $classNode)
    {
        $classNamespace = $classNode->getNamespaceName();
        if (strpos($classNamespace, 'Dependency\\') !== false) {
            return;
        }

        $className = $classNode->getName();
        if (!preg_match('/(' . implode('|', $this->apiClasses) . ')$/', $className)) {
            return;
        }

        /** @var \PHPMD\Node\InterfaceNode $classNode */
        /** @var \PDepend\Source\AST\ASTMethod $method */
        foreach ($classNode->getMethods() as $method) {
            if (!$method->isPublic()) {
                continue;
            }
            $this->applyOnPublicMethod($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode|\PDepend\Source\AST\ASTMethod $methodNode
     *
     * @return void
     */
    protected function applyOnPublicMethod(MethodNode $methodNode)
    {
        $methodDocBlock = $methodNode->getComment();
        if (preg_match('/\{\@inheritDoc\}/', $methodDocBlock)) {
            return;
        }

        $this->addViolation(
            $methodNode,
            [
                sprintf(
                    'The public class method %s does not contain an {@inheritDoc} tag ' .
                    'which violates rule: "%s"',
                    $methodNode->getFullQualifiedName(),
                    static::RULE
                ),
            ]
        );
    }
}

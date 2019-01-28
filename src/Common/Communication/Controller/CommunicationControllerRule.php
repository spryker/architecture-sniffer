<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Communication\Controller;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class CommunicationControllerRule extends AbstractRule implements ClassAware
{
    public const RULE = 'All public controller methods have the suffix `*Action`.';

    /**
     * @var array
     */
    protected $notActionMethodNames = [
        'initialize',
        '__construct',
    ];

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
        if (preg_match('(\\\\[^\\\\]+Controller$)', $node->getFullQualifiedName()) === 0) {
            return;
        }
        if ($node->getName() === 'AbstractController') {
            return;
        }

        /** @var \PHPMD\Node\ClassNode $node */
        foreach ($node->getMethods() as $method) {
            $this->applyPublicMethodsHaveActionSuffix($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyPublicMethodsHaveActionSuffix(MethodNode $method)
    {
        if (substr($method->getName(), -6, 6) === 'Action') {
            return;
        }

        /** @var \PDepend\Source\AST\ASTMethod $method */
        if ($method->isProtected() || $method->isPrivate()) {
            return;
        }

        if ($this->isNotActionMethod($method->getName())) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The controller method %s is not suffixed with "Action" which violates rule "' . static::RULE . '"',
                    $method->getFullQualifiedName()
                ),
            ]
        );
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isNotActionMethod($name)
    {
        return in_array($name, $this->notActionMethodNames);
    }
}

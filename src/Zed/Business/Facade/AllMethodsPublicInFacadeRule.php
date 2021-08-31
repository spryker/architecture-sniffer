<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class AllMethodsPublicInFacadeRule extends AbstractFacadeRule implements ClassAware
{
    public const RULE = 'A facade must only contain public methods.';

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
        if (!$this->isFacade($node) || $this->isAbstractFacade($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\AbstractNode|\PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    protected function applyRule(ClassNode $node)
    {
        foreach ($node->getMethods() as $method) {
            $this->checkVisibility($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function checkVisibility(MethodNode $method)
    {
        if (!$method->getNode()->isPublic()) {
            $message = sprintf(
                'The method "%s" is not public which violates rule "%s"',
                $method->getFullQualifiedName(),
                static::RULE
            );

            $this->addViolation($method, [$message]);
        }
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Business\Facade;

use ArchitectureSniffer\Common\DeprecationTrait;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class FacadeRule extends AbstractFacadeRule implements ClassAware
{
    use DeprecationTrait;

    /**
     * @var string
     */
    public const RULE = 'A facade must not have properties. It must also not contain any instantiations, only delegation.';

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

        $this->applyStatelessThereAreNoProperties($node);

        foreach ($node->getMethods() as $method) {
            if ($this->isMethodDeprecated($method)) {
                continue;
            }

            $this->applyNoInstantiationsWithNew($method);
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode $class
     *
     * @return void
     */
    protected function applyStatelessThereAreNoProperties(ClassNode $class)
    {
        if (count($class->getProperties()) === 0) {
            return;
        }

        $this->addViolation(
            $class,
            [
                sprintf(
                    'There are properties in class `%s` which violates rule "' . static::RULE . '"',
                    $class->getFullQualifiedName(),
                ),
            ],
        );
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyNoInstantiationsWithNew(MethodNode $method)
    {
        if (count($method->findChildrenOfType('AllocationExpression')) === 0) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The method `%s()` uses `new` to instantiate an object which violates rule "No instantiations with \'new\'"',
                    $method->getFullQualifiedName(),
                ),
            ],
        );
    }
}

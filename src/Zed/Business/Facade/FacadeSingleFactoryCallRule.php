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

class FacadeSingleFactoryCallRule extends AbstractFacadeRule implements ClassAware
{
    /**
     * @var string
     */
    public const RULE = 'Every Facade method should have no more than one factory call.';

    /**
     * @var string
     */
    protected const PSEUDO_VAR_THIS = '$this';

    /**
     * @var string
     */
    protected const OBJECT_OPERATOR = '->';

    /**
     * @var string
     */
    protected const GET_FACTORY_METHOD = 'getFactory';

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
     * @param \PHPMD\AbstractNode|\PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    protected function applyRule(ClassNode $node)
    {
        foreach ($node->getMethods() as $method) {
            $this->checkStatements($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function checkStatements(MethodNode $method)
    {
        $factoryCalls = $this->getFactoryCallsNumber($method);
        if ($factoryCalls < 2) {
            return;
        }

        $message = sprintf(
            'The method %s has %s factory calls and violates the rule "%s".',
            $method->getFullQualifiedName(),
            $factoryCalls,
            static::RULE,
        );

        $this->addViolation($method, [$message]);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function getFactoryCallsNumber(MethodNode $method)
    {
        $factoryCallNumber = 0;
        $primaryPrefixes = $method->findChildrenOfType('MemberPrimaryPrefix');

        foreach ($primaryPrefixes as $key => $primaryPrefix) {
            if (!$this->isChildNameEqual($primaryPrefix, 0, static::PSEUDO_VAR_THIS)) {
                continue;
            }

            if (!$this->isChildNameEqual($primaryPrefix, 1, static::OBJECT_OPERATOR)) {
                continue;
            }

            if (!isset($primaryPrefixes[$key + 1])) {
                continue;
            }

            if ($this->isChildNameEqual($primaryPrefixes[$key + 1], 0, static::GET_FACTORY_METHOD)) {
                $factoryCallNumber++;
            }
        }

        return $factoryCallNumber;
    }

    /**
     * @param \PHPMD\AbstractNode $primaryPrefix
     * @param int $index
     * @param string $value
     *
     * @return bool
     */
    protected function isChildNameEqual($primaryPrefix, $index, $value)
    {
        return $primaryPrefix->getChild($index)->getName() === $value;
    }
}

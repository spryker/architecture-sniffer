<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Bridge;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;
use ReflectionMethod;
use ReflectionType;

class BridgeFacadeMethodsRule extends SprykerAbstractRule implements ClassAware
{
    /**
     * @var string
     */
    protected const RULE = 'Based on naming convention the bridge facade method must follow CRUD signature.';

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
        if (
            preg_match('([A-Za-z0-9]+Bridge$)', $node->getName()) === 0 ||
            preg_match('#.*\\\\Dependency\\\\Facade.*#', $node->getNamespaceName()) === 0 ||
            !$node instanceof ClassNode
        ) {
            return;
        }
        $bridgeName = $node->getFullQualifiedName();

        foreach ($node->getMethods() as $method) {
            $interfaceMethodName = sprintf('%s::%s', $bridgeName, $method->getName());
            $interfaceMethodReflection = new ReflectionMethod($interfaceMethodName);
            $methodReturnType = $interfaceMethodReflection->getReturnType();

            if (preg_match('/^(get|read|find)/', $method->getName())) {
                $this->verifyGetMethod($method, $methodReturnType);

                continue;
            }

            if (preg_match('/^(delete|remove)/', $method->getName())) {
                $this->verifyDeleteMethod($method, $methodReturnType);

                continue;
            }

            if (preg_match('/^(create|add)/', $method->getName())) {
                $this->verifyCreateMethod($method, $methodReturnType);

                continue;
            }

            if (preg_match('/^(update|change)/', $method->getName())) {
                $this->verifyUpdateMethod($method, $methodReturnType);

                continue;
            }

            if (strpos($method->getName(), 'save') === 0) {
                $this->addViolation($method, [sprintf(
                    'Method `%s()` must have `public function [update|create]<DomainEntity>Collection(<DomainEntity>CollectionRequestTransfer): <DomainEntity>CollectionResponseTransfer` signature.',
                    $method->getName(),
                )]);

                continue;
            }
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param \ReflectionType|null $methodReturnType
     *
     * @return void
     */
    protected function verifyGetMethod(MethodNode $method, ?ReflectionType $methodReturnType): void
    {
        $parameters = $method->getNode()->getParameters();

        if (
            $method->getParameterCount() !== 1 ||
            !$parameters[0]->getClass() ||
            !$methodReturnType ||
            preg_match('/^get(?<domainEntity>\w+)Collection$/', $method->getName(), $methodNameMatches) === 0 ||
            preg_match('/^(?<domainEntity>\w+)CriteriaTransfer$/', $parameters[0]->getClass()->getName(), $methodParameterMatches) === 0 ||
            preg_match('/^Generated\\\\Shared\\\\Transfer\\\\(?<domainEntity>\w+)CollectionTransfer$/', $methodReturnType->getName(), $methodReturnTypeMatches) === 0 ||
            count(array_unique([$methodNameMatches['domainEntity'], $methodParameterMatches['domainEntity'], $methodReturnTypeMatches['domainEntity']])) !== 1
        ) {
            $this->addViolation($method, [sprintf(
                'Method `%s()` must have `public function get<DomainEntity>Collection(<DomainEntity>CriteriaTransfer): <DomainEntity>CollectionTransfer` signature.',
                $method->getName(),
            )]);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param \ReflectionType|null $methodReturnType
     *
     * @return void
     */
    protected function verifyUpdateMethod(MethodNode $method, ?ReflectionType $methodReturnType): void
    {
        $parameters = $method->getNode()->getParameters();

        if (
            $method->getParameterCount() !== 1 ||
            !$parameters[0]->getClass() ||
            !$methodReturnType ||
            preg_match('/^update(?<domainEntity>\w+)Collection$/', $method->getName(), $methodNameMatches) === 0 ||
            preg_match('/^(?<domainEntity>\w+)CollectionRequestTransfer$/', $parameters[0]->getClass()->getName(), $methodParameterMatches) === 0 ||
            preg_match('/^Generated\\\\Shared\\\\Transfer\\\\(?<domainEntity>\w+)CollectionResponseTransfer$/', $methodReturnType->getName(), $methodReturnTypeMatches) === 0 ||
            count(array_unique([$methodNameMatches['domainEntity'], $methodParameterMatches['domainEntity'], $methodReturnTypeMatches['domainEntity']])) !== 1
        ) {
            $this->addViolation($method, [sprintf(
                'Method `%s()` must have `public function update<DomainEntity>Collection(<DomainEntity>CollectionRequestTransfer): <DomainEntity>CollectionResponseTransfer` signature.',
                $method->getName(),
            )]);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param \ReflectionType|null $methodReturnType
     *
     * @return void
     */
    protected function verifyDeleteMethod(MethodNode $method, ?ReflectionType $methodReturnType): void
    {
        $parameters = $method->getNode()->getParameters();

        if (
            $method->getParameterCount() !== 1 ||
            !$parameters[0]->getClass() ||
            !$methodReturnType ||
            preg_match('/^delete(?<domainEntity>\w+)Collection$/', $method->getName(), $methodNameMatches) === 0 ||
            preg_match('/^(?<domainEntity>\w+)CollectionDeleteCriteriaTransfer$/', $parameters[0]->getClass()->getName(), $methodParameterMatches) === 0 ||
            preg_match('/^Generated\\\\Shared\\\\Transfer\\\\(?<domainEntity>\w+)CollectionResponseTransfer$/', $methodReturnType->getName(), $methodReturnTypeMatches) === 0 ||
            count(array_unique([$methodNameMatches['domainEntity'], $methodParameterMatches['domainEntity'], $methodReturnTypeMatches['domainEntity']])) !== 1
        ) {
            $this->addViolation($method, [sprintf(
                'Method `%s()` must have `public function delete<DomainEntity>Collection(<DomainEntity>CollectionDeleteCriteriaTransfer): <DomainEntity>CollectionResponseTransfer` signature.',
                $method->getName(),
            )]);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param \ReflectionType|null $methodReturnType
     *
     * @return void
     */
    protected function verifyCreateMethod(MethodNode $method, ?ReflectionType $methodReturnType): void
    {
        $parameters = $method->getNode()->getParameters();

        if (
            $method->getParameterCount() !== 1 ||
            !$parameters[0]->getClass() ||
            !$methodReturnType ||
            preg_match('/^create(?<domainEntity>\w+)Collection$/', $method->getName(), $methodNameMatches) === 0 ||
            preg_match('/^(?<domainEntity>\w+)CollectionRequestTransfer$/', $parameters[0]->getClass()->getName(), $methodParameterMatches) === 0 ||
            preg_match('/^Generated\\\\Shared\\\\Transfer\\\\(?<domainEntity>\w+)CollectionResponseTransfer$/', $methodReturnType->getName(), $methodReturnTypeMatches) === 0 ||
            count(array_unique([$methodNameMatches['domainEntity'], $methodParameterMatches['domainEntity'], $methodReturnTypeMatches['domainEntity']])) !== 1
        ) {
            $this->addViolation($method, [sprintf(
                'Method `%s()` must have `public function create<DomainEntity>Collection(<DomainEntity>CollectionRequestTransfer): <DomainEntity>CollectionResponseTransfer` signature.',
                $method->getName(),
            )]);
        }
    }
}

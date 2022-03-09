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
    protected const RULE = 'The bridge facade method must follow CRUD signature';

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
        $dependencyModuleName = $this->getDependencyModuleName($node);
        $bridgeName = $node->getFullQualifiedName();

        foreach ($node->getMethods() as $method) {
            $interfaceMethodName = sprintf('%s::%s', $bridgeName, $method->getName());
            $interfaceMethodReflection = new ReflectionMethod($interfaceMethodName);
            $methodReturnType = $interfaceMethodReflection->getReturnType();

            if (preg_match('/^(delete|remove)/', $method->getName())) {
                $this->verifyDeleteMethod($method, $methodReturnType, $dependencyModuleName);
            }
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param \ReflectionType|null $methodReturnType
     * @param string $dependencyModuleName
     *
     * @return void
     */
    protected function verifyDeleteMethod(MethodNode $method, ?ReflectionType $methodReturnType, string $dependencyModuleName): void
    {
        $violations = [];

        $parameters = $method->getNode()->getParameters();

        if (preg_match('/^delete\w+Collection$/', $method->getName()) === 0) {
            $violations[] = sprintf(
                'Method %s must have delete%sCollection name.',
                $method->getName(),
                $dependencyModuleName,
            );
        }

        if (
            $method->getParameterCount() !== 1 ||
            !$parameters[0]->getClass() ||
            sprintf('%sCollectionDeleteCriteriaTransfer', $dependencyModuleName) !== $parameters[0]->getClass()->getName()
        ) {
            $violations[] = sprintf(
                '`%s` method parameter must have only %sDeleteCriteriaTransfer parameter.',
                $method->getName(),
                $dependencyModuleName,
            );
        }

        if (
            !$methodReturnType ||
            sprintf('%s\%sCollectionResponseTransfer', 'Generated\Shared\Transfer', $dependencyModuleName) !== $methodReturnType->getName()
        ) {
            $violations[] = sprintf(
                'Return type for %s method must have %sCollectionResponseTransfer name.',
                $method->getName(),
                $dependencyModuleName,
            );
        }

        if ($violations) {
            $this->addViolation($method, $violations);
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     *
     * @return string
     */
    protected function getDependencyModuleName(ClassNode $node): string
    {
        foreach ($node->getMethods() as $method) {
            if ($method->getName() === '__construct') {
                $constructorComment = $method->getNode()->getComment();

                if (!$constructorComment) {
                    return '';
                }

                $firstParameter = $method->getNode()->getParameters()[0];

                if (!$firstParameter) {
                    return '';
                }

                $pattern = '#@param[\s]+(?<interfaceName>.*)[\s]+' . preg_quote($firstParameter->getName()) . '#is';

                if (preg_match($pattern, $constructorComment, $matches) === 0) {
                    return '';
                }

                return $this->getModuleName($matches['interfaceName']);
            }
        }

        return '';
    }
}

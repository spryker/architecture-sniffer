<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Bridge;

use ArchitectureSniffer\Zed\Dependency\Bridge\AbstractBridgeRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class BridgeEntityFacadeFetchFunction extends AbstractBridgeRule implements MethodAware
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'An entity fetching function in a facade should always follow the following rules:
        - Name follows convention: getDomainEntityCollection
        - Have a transfer object based on Domain Entity name (e.g. DomainEntityCriteriaTransfer) as input
        - Return a transfer object with a collection containing results of fetching';
    }

    /**
     * @inheritDoc
     */
    public function apply(AbstractNode $node): void
    {
        if (!$this->isBridge($node) || !$this->isEntityFetchingFacadeBridge($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isEntityFetchingFacadeBridge(AbstractNode $node): bool
    {
        $namespaceParts = explode('\\', $node->getNode()->getParent()->getNamespace()->getName());

        if ($namespaceParts && count($namespaceParts) > 2 && in_array($namespaceParts[(count($namespaceParts) - 1)], ['Client', 'Facade'])) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method): void
    {
        if (!preg_match('/(get|find|fetch).+/', $method->getName())) {
            return;
        }

        $this->applyNamingRules($method);
        $this->applyParameterRules($method);
        $this->applyReturnRules($method);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyNamingRules(MethodNode $method): void
    {
        if (!str_ends_with($method->getName(), 'Collection') || !str_starts_with($method->getName(), 'get')) {
            $this->addViolation($method, [
                sprintf(
                    'The method %s in bridge %s violates the naming convention of entity fetching functions
                    getDomainEntityCollection, please use a convention matching entity fetching function in the
                    corresponding facade or consider introducing one',
                    $method->getName(),
                    $method->getParentName()
                ),
            ]);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyParameterRules(MethodNode $method): void
    {
        if ($method->getParameterCount() === 1) {
            $firstParameter = $method->getParameters()[0];
            if (!$firstParameter->getClass() || $firstParameter->getClass()->getNamespaceName() !== 'Generated\Shared\Transfer') {
                $this->addViolation($method, [
                    sprintf(
                        'The method %s in bridge %s violates the parameter convention of entity fetching functions
                        which is having only one Transfer Object as input to the function,  please use a convention
                        matching entity fetching function in the corresponding facade or consider introducing one',
                        $method->getName(),
                        $method->getParentName()
                    ),
                ]);
            }

            if (
                $firstParameter->getClass() &&
                (
                    !str_ends_with($firstParameter->getClass()->getName(), 'CriteriaTransfer') ||
                    !str_starts_with($firstParameter->getClass()->getName(), $this->getEntityFromMethodName($method->getName()))
                )
            ) {
                $this->addViolation($method, [
                    sprintf(
                        'The method %s in bridge %s violates the parameter convention of entity fetching functions
                        which is having a DomainEntityCriteriaTransfer as input to the function,  please use a
                        convention matching entity fetching function in the corresponding facade or consider introducing one',
                        $method->getName(),
                        $method->getParentName()
                    ),
                ]);
            }

            return;
        }

        $this->addViolation($method, [
            sprintf(
                'The method %s in bridge %s violates the parameter convention of entity fetching functions
                    which is having only parameter input to the function,  please use a convention matching
                    entity fetching function in the corresponding facade or consider introducing one',
                $method->getName(),
                $method->getParentName()
            ),
        ]);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyReturnRules(MethodNode $method): void
    {
        /** @var \PDepend\Source\AST\AbstractASTClassOrInterface|null $returnType */
        $returnType = $method->getNode()->getReturnClass();
        $entityType = $this->getEntityFromMethodName($method->getName());

        if (
            !$returnType
            || !$entityType
            || $returnType->getNamespaceName() !== 'Generated\Shared\Transfer'
            || !str_ends_with($returnType->getName(), $entityType . 'CollectionTransfer')
        ) {
            $this->addViolation($method, [
                sprintf(
                    'The method %s in bridge %s violates the return type convention of entity fetching functions
                    which is returning a DomainEntityCollection Transfer Object, please use a convention matching
                    entity fetching function in the corresponding facade or consider introducing one',
                    $method->getName(),
                    $method->getParentName()
                ),
            ]);
        }
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    protected function getEntityFromMethodName(string $name): ?string
    {
        if (str_starts_with($name, 'get') && str_ends_with($name, 'Collection')) {
            preg_match('/get(.+)Collection/', $name, $entityName);

            return $entityName[1];
        }

        return null;
    }
}

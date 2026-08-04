<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\MethodAware;

class SingletonInstanceInDependencyProviderOnlyRule extends AbstractRule implements MethodAware
{
    public const string RULE = 'Singleton getInstance() initialisation should be in Dependency Provider only.';

    protected const string GET_INSTANCE_METHOD_NAME = '/^(getInstance)$/';

    public function getDescription(): string
    {
        return static::RULE;
    }

    public function apply(AbstractNode $node): void
    {
        if ($this->isDependencyProvider($node)) {
            return;
        }

        $parent = $node->getNode()->getParent();
        $className = $parent->getNamespaceName() . '\\' . $parent->getName();

        $ignoreClassPattern = $this->getStringProperty('ignoreClassPattern', '');

        if ($ignoreClassPattern !== '' && preg_match($ignoreClassPattern, $className) === 1) {
            return;
        }

        foreach ($node->findChildrenOfType('MethodPostfix') as $classUsage) {
            $methodName = $classUsage->getNode()->getImage();

            if ($this->isGetInstance($methodName) === false) {
                continue;
            }

            $this->addViolation(
                $node,
                [
                    sprintf(
                        'The method %s in %s uses ::getInstance. It can not be used outside of DependencyProvider',
                        $node->getName(),
                        $className,
                    ),
                ],
            );
        }
    }

    protected function isDependencyProvider(AbstractNode $node): bool
    {
        $parent = $node->getNode()->getParent();
        $className = $parent->getNamespaceName() . '\\' . $parent->getName();

        if (preg_match('/\\\\' . '(?:Client|Yves|Glue|Zed|Service)' . '\\\\.*\\\\\w+DependencyProvider$/', $className)) {
            return true;
        }

        return false;
    }

    protected function isGetInstance(string $methodName): bool
    {
        if (preg_match(static::GET_INSTANCE_METHOD_NAME, $methodName)) {
            return true;
        }

        return false;
    }
}

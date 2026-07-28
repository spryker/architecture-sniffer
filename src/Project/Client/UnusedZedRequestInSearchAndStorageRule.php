<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Client;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\MethodAware;

class UnusedZedRequestInSearchAndStorageRule extends AbstractRule implements MethodAware
{
    protected const string RULE = 'There should be no Zed Request in Search And Storage Client.';

    protected const string ZED_REQUEST_METHOD_NAME = '/^(zedRequest)$/';

    public function getDescription(): string
    {
        return static::RULE;
    }

    public function apply(AbstractNode $node): void
    {
        if (!$this->isSearchOrStorageClientDependencyProvider($node)) {
            return;
        }

        foreach ($node->findChildrenOfType('MethodPostfix') as $classUsage) {
            $methodName = $classUsage->getNode()->getImage();

            if ($this->isZedRequest($methodName) === false) {
                continue;
            }

            $this->addViolation(
                $node,
                [
                    sprintf(
                        'The method %s uses ZedRequest. It can not be used in Client Search/Storage DependencyProvider',
                        $node->getName(),
                    ),
                ],
            );

            break;
        }
    }

    protected function isSearchOrStorageClientDependencyProvider(AbstractNode $node): bool
    {
        $parent = $node->getNode()->getParent();
        $className = $parent->getNamespaceName() . '\\' . $parent->getName();

        $ignoreClassPattern = $this->getStringProperty('ignoreClassPattern', '');

        if ($ignoreClassPattern !== '' && preg_match($ignoreClassPattern, $className) === 1) {
            return false;
        }

        if (preg_match('/\\\\' . 'Client' . '\\\\.*\\\\\w+(?:Search|Storage)DependencyProvider$/', $className)) {
            return true;
        }

        return false;
    }

    protected function isZedRequest(string $methodName): bool
    {
        if (preg_match(static::ZED_REQUEST_METHOD_NAME, $methodName)) {
            return true;
        }

        return false;
    }
}

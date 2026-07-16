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
    /**
     * @var string
     */
    protected const RULE = 'There should be no Zed Request in Search And Storage Client.';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return static::RULE;
    }

    /**
     * @var string
     */
    protected const ZED_REQUEST_METHOD_NAME = '/^(zedRequest)$/';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
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
        }
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isSearchOrStorageClientDependencyProvider(AbstractNode $node): bool
    {
        $parent = $node->getNode()->getParent();
        $className = $parent->getNamespaceName() . '\\' . $parent->getName();

        if (preg_match('/\\\\' . 'Client' . '\\\\.*\\\\\w+(?:Search|Storage)DependencyProvider$/', $className)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    protected function isZedRequest(string $methodName): bool
    {
        if (preg_match(static::ZED_REQUEST_METHOD_NAME, $methodName)) {
            return true;
        }

        return false;
    }
}

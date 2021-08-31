<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Dependency\Bridge;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class BridgeConstructorArgumentsRule extends AbstractBridgeRule implements MethodAware
{
    public const RULE = 'A bridge should only have a single internal dependency in constructor. It is also used only on core, not in projects.';

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
        if (!$this->isBridge($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
    {
        if ($method->getName() !== '__construct') {
            return;
        }

        $internalDependenciesCount = $this->getInternalDependenciesCount($method);

        if ($internalDependenciesCount <= 1) {
            return;
        }

        $message = sprintf(
            'The %s is having %s internal dependencies which violates the rule "%s"',
            $method->getFullQualifiedName(),
            $internalDependenciesCount,
            static::RULE
        );

        $this->addViolation($method, [$message]);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return int
     */
    protected function getInternalDependenciesCount(MethodNode $method): int
    {
        preg_match_all('/@param \\\\Spryker.*\\\\/', $method->getComment(), $matches);

        return count($matches[0]);
    }
}

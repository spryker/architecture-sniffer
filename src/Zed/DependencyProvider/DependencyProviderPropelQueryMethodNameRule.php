<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\DependencyProvider;

use ArchitectureSniffer\Common\DependencyProvider\AbstractDependencyProviderRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class DependencyProviderPropelQueryMethodNameRule extends AbstractDependencyProviderRule implements MethodAware
{
    public const RULE = 'Add propel query methods must be named like add*PropelQuery() in dependency provider.';
    protected const PATTERN_PROPEL_QUERY_DEPENDENCY_PROVIDER_METHOD_NAME = '/^add([a-zA-Z]+)PropelQuery$/';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (!$this->isDependencyProvider($node, 'Zed')) {
            return;
        }

        if (!$this->hasPropelQueryAllocationExpression($node)) {
            return;
        }

        $this->applyPropelQueryMethodNameRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyPropelQueryMethodNameRule(MethodNode $method): void
    {
        $methodName = $method->getName();

        if (preg_match(static::PATTERN_PROPEL_QUERY_DEPENDENCY_PROVIDER_METHOD_NAME, $methodName) !== 0) {
            return;
        }

        $this->addViolationMessage($method);
    }
}

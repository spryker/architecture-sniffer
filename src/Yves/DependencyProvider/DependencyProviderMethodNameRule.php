<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Yves\DependencyProvider;

use ArchitectureSniffer\Common\DependencyProvider\AbstractDependencyProviderRule;
use PHPMD\AbstractNode;
use PHPMD\Rule\MethodAware;

class DependencyProviderMethodNameRule extends AbstractDependencyProviderRule implements MethodAware
{
    /**
     * @var array
     */
    protected $allowedProvideMethodNames = [
        'provideDependencies',
    ];

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
        if (!$this->isDependencyProvider($node, 'Yves')) {
            return;
        }

        $this->applyRule($node, $this->allowedProvideMethodNames);
    }
}

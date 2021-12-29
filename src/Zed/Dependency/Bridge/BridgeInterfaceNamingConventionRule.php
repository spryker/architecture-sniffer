<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Dependency\Bridge;

use PHPMD\AbstractNode;

class BridgeInterfaceNamingConventionRule extends AbstractBridgeRule
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (!$this->isBridgeInterface($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return void
     */
    protected function applyRule(AbstractNode $node)
    {
        $className = $node->getFullQualifiedName();
        $concreteClassName = substr($className, 0, -9) . 'Bridge';

        $classExists = class_exists($concreteClassName);

        if (!$classExists) {
            return;
        }

        $message = sprintf(
            'Bridge interface `%s` has no matching concrete `%s`',
            $className,
            $concreteClassName,
        );

        $this->addViolation($node, [$message]);
    }
}

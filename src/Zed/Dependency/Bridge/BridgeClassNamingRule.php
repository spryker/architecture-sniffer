<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Zed\Dependency\Bridge;

use PHPMD\AbstractNode;

class BridgeClassNamingRule extends AbstractBridgeRule
{
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
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return void
     */
    protected function applyRule(AbstractNode $node)
    {
        $className = $node->getFullQualifiedName();
        $result = preg_match('/([A-z].*)(To)([A-z].*)Bridge/', $className);

        if ($result === 1) {
            return;
        }

        $message = sprintf(
            'The bridge class name `%s` is not matching the convention `<ModuleName>To<ModuleName>Bridge`',
            $className,
        );

        $this->addViolation($node, [$message]);
    }
}

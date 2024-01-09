<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

use PHPMD\AbstractNode;

trait ClassNameTrait
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isBridgeClass(AbstractNode $node): bool
    {
        if (
            preg_match('([A-Za-z0-9]+Bridge$)', $node->getName()) === 0 ||
            preg_match('#.*\\\\Dependency\\\\.*#', $node->getNamespaceName()) === 0
        ) {
            return false;
        }

        return true;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Shared;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;
use PHPMD\Rule\InterfaceAware;

class ModuleConstantsPathRule extends AbstractRule implements ClassAware, InterfaceAware
{
    protected const RULE = 'An environment configuration must lie in "Shared" layer.';

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
        if (preg_match('#[A-Za-z0-9]+Constants$#', $node->getName()) === 0 ||
            preg_match('#\\\\Shared\\\\#', $node->getNamespaceName()) !== 0) {
            return;
        }

        $message = sprintf(
            'The environment configuration is not lie in "Shared" layer. That violates the rule "%s"',
            static::RULE
        );

        $this->addViolation($node, [$message]);
    }
}

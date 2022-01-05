<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\Common\DeprecationTrait;
use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Rule\ClassAware;

class NoClientInFrontendModuleRule extends SprykerAbstractRule implements ClassAware
{
    use DeprecationTrait;

    /**
     * @var string
     */
    protected const RULE = 'There should be no Client layer in frontend modules.';

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
        if (preg_match('#^SprykerShop\\\\Client\\\\#', $node->getFullQualifiedName()) === 0) {
            return;
        }

        if ($this->isClassDeprecated($node)) {
            return;
        }

        $this->addViolation(
            $node,
            [
                sprintf(
                    'The class `%s` is in Client layer which violates rule "' . static::RULE . '"',
                    $node->getFullQualifiedName(),
                ),
            ],
        );
    }
}

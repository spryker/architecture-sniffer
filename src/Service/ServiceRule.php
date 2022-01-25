<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Service;

use ArchitectureSniffer\Common\ImplementsApiInterfaceRule;
use PHPMD\AbstractNode;
use PHPMD\Rule\ClassAware;

class ServiceRule extends ImplementsApiInterfaceRule implements ClassAware
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isApplicable(AbstractNode $node): bool
    {
        return $node->getName() !== 'AbstractService' && parent::isApplicable($node);
    }

    /**
     * @phpstan-return non-empty-string
     *
     * @return string
     */
    protected function getClassRegex(): string
    {
        return '(\\\\Client\\\\[A-Za-z]+\\\\[A-Za-z]+Client$)';
    }
}

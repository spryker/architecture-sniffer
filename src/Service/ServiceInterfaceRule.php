<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Service;

use ArchitectureSniffer\Common\ApiInterfaceRule;
use PHPMD\Rule\InterfaceAware;

class ServiceInterfaceRule extends ApiInterfaceRule implements InterfaceAware
{
    /**
     * @phpstan-return non-empty-string
     *
     * @return string
     */
    protected function getClassRegex(): string
    {
        return '(\\\\Service\\\\.+ServiceInterface$)';
    }
}

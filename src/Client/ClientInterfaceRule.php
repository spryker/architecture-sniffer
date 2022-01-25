<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\Common\ApiInterfaceRule;
use PHPMD\Rule\InterfaceAware;

class ClientInterfaceRule extends ApiInterfaceRule implements InterfaceAware
{
    /**
     * @phpstan-return non-empty-string
     *
     * @return string
     */
    protected function getClassRegex(): string
    {
        return '(\\\\Client\\\\[A-Za-z]+\\\\[A-Za-z]+ClientInterface$)';
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\Common\ImplementsApiInterfaceRule;
use PHPMD\Rule\ClassAware;

class ClientRule extends ImplementsApiInterfaceRule implements ClassAware
{
    /**
     * @var string
     */
    protected $classRegex = '(\\\\Client\\\\[A-Za-z]+\\\\[A-Za-z]+Client$)';
}

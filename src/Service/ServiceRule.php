<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Service;

use ArchitectureSniffer\Common\ImplementsApiInterfaceRule;
use PHPMD\Rule\ClassAware;

class ServiceRule extends ImplementsApiInterfaceRule implements ClassAware
{
    /**
     * @var string
     */
    protected $classRegex = '(\\\\Service\\\\.+Service$)';
}

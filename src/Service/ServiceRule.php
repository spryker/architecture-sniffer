<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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

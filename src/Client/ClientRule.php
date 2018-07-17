<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\Common\ImplementsApiInterfaceRule;
use PHPMD\Rule\ClassAware;

class ClientRule extends ImplementsApiInterfaceRule implements ClassAware
{
    /**
     * @var string
     */
    protected $classRegex = '(\\\\Client\\\\.+Client$)';
}

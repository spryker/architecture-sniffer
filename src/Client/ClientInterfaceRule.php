<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\Common\ApiInterfaceRule;
use PHPMD\Rule\InterfaceAware;

class ClientInterfaceRule extends ApiInterfaceRule implements InterfaceAware
{
    /**
     * @var string
     */
    protected $classRegex = '(\\\\Client\\\\.+ClientInterface$)';
}

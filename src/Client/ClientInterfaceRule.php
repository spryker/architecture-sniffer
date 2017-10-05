<?php

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\Common\APIInterfaceRule;
use PHPMD\Rule\InterfaceAware;

class ClientInterfaceRule extends APIInterfaceRule implements InterfaceAware
{

    /** @var string */
    protected $classRegex = '(\\\\Client\\\\.+ClientInterface$)';

}

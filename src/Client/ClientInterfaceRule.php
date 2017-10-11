<?php

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\Common\ApiInterfaceRule;
use PHPMD\Rule\InterfaceAware;

class ClientInterfaceRule extends ApiInterfaceRule implements InterfaceAware
{

    /** @var string */
    protected $classRegex = '(\\\\Client\\\\.+ClientInterface$)';

}

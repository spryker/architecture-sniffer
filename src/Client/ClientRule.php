<?php

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\Common\ImplementsApiInterfaceRule;
use PHPMD\Rule\ClassAware;

class ClientRule extends ImplementsApiInterfaceRule implements ClassAware
{

    /** @var string */
    protected $classRegex = '(\\\\Client\\\\.+Client$)';

}

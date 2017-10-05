<?php

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\Common\ImplementsAPIInterfaceRule;
use PHPMD\Rule\ClassAware;

class ClientRule extends ImplementsAPIInterfaceRule implements ClassAware
{

    /** @var string */
    protected $classRegex = '(\\\\Client\\\\.+Client$)';

}

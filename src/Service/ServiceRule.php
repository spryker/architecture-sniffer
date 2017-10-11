<?php

namespace ArchitectureSniffer\Service;

use ArchitectureSniffer\Common\ImplementsApiInterfaceRule;
use PHPMD\Rule\ClassAware;

class ServiceRule extends ImplementsApiInterfaceRule implements ClassAware
{

    /** @var string */
    protected $classRegex = '(\\\\Service\\\\.+Service$)';

}

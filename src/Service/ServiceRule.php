<?php

namespace ArchitectureSniffer\Service;

use ArchitectureSniffer\Common\ImplementsAPIInterfaceRule;
use PHPMD\Rule\ClassAware;

class ServiceRule extends ImplementsAPIInterfaceRule implements ClassAware
{

    /** @var string */
    protected $classRegex = '(\\\\Service\\\\.+Service$)';

}

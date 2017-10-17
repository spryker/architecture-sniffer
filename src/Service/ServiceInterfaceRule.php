<?php

namespace ArchitectureSniffer\Service;

use ArchitectureSniffer\Common\ApiInterfaceRule;
use PHPMD\Rule\InterfaceAware;

class ServiceInterfaceRule extends ApiInterfaceRule implements InterfaceAware
{
    /**
     * @var string
     */
    protected $classRegex = '(\\\\Service\\\\.+ServiceInterface$)';
}

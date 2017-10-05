<?php

namespace ArchitectureSniffer\Service;

use ArchitectureSniffer\Common\APIInterfaceRule;
use PHPMD\Rule\InterfaceAware;

class ServiceInterfaceRule extends APIInterfaceRule implements InterfaceAware
{

    /** @var string */
    protected $classRegex = '(\\\\Service\\\\.+ServiceInterface$)';

}

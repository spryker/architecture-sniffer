<?php

namespace ArchitectureSniffer\Business\Facade;

use PHPMD\AbstractRule;

abstract class AbstractFacadeRule extends AbstractRule
{

    /**
     * @param $className
     *
     * @return bool
     */
    protected function isFacade($className)
    {
        if (preg_match('/\\\\Zed\\\\.*\\\\Business\\\\.*Facade$/', $className)) {
            return true;
        }

        return false;
    }

}

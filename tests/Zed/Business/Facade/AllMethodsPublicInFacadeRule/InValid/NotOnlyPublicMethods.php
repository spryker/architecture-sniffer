<?php

namespace ArchitectureSniffer\Zed\Bundle\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

class BundleFacade extends AbstractFacade implements BundleFacadeInterface
{

    /**
     * @return void
     */
    protected function onlyPublicMethodsAllowed()
    {

    }

}

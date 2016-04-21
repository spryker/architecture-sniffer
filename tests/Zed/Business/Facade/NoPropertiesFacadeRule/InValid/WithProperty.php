<?php

namespace ArchitectureSniffer\Zed\Bundle\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

class BundleFacade extends AbstractFacade implements BundleFacadeInterface
{

    /**
     * Properties are not allowed in Facade's
     *
     * @var string
     */
    protected $property;

}

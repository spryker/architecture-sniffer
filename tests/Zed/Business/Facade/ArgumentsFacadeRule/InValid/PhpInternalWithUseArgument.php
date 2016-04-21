<?php

namespace ArchitectureSniffer\Zed\Bundle\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use \DateTime;

class BundleFacade extends AbstractFacade implements BundleFacadeInterface
{

    /**
     * @param \DateTime $dateTime
     *
     * @return void
     */
    public function phpInternalNotAllowedAsArgument(DateTime $dateTime)
    {

    }

}

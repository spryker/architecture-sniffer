<?php

namespace ArchitectureSniffer\Zed\Bundle\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\SomeTransfer;

class BundleFacade extends AbstractFacade implements BundleFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\SomeTransfer $someTransfer
     *
     * @return void
     */
    public function transferIsAllowed(SomeTransfer $someTransfer)
    {

    }

}

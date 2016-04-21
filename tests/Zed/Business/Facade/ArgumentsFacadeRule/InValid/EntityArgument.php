<?php

namespace ArchitectureSniffer\Zed\Bundle\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

class BundleFacade extends AbstractFacade implements BundleFacadeInterface
{

    /**
     * @param \Orm\Zed\User\Persistence\SpySomeEntity $someEntity
     *
     * @return void
     */
    public function entitiesNotAllowedAsArgument(SpySomeEntity $someEntity)
    {

    }

}

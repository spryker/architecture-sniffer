<?php

namespace Spyryker\Zed\Module\Business;

use Orm\Zed\Persistence\SpyTestEntity;

class TestFacade
{
    /**
     * @return void
     */
    protected function createTestSpyEntity(): void
    {
        $spyTestEntity =  new SpyTestEntity();
    }
}

<?php

namespace Spyryker\Zed\Module\Persistence\Repository;

use Orm\Zed\Persistence\SpyTestEntity;

class TestRepository
{
    /**
     * @return void
     */
    protected function createTestSpyEntity(): void
    {
        $spyTestEntity = new SpyTestEntity();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo\Persistence;

class FooRepository
{
    /**
     * @return void
     */
    public function findAndPersistFoo(): void
    {
        $this->getEntityManager()->save();
    }

    /**
     * @return \Pyz\Zed\Foo\Persistence\FooRepository
     */
    protected function getEntityManager()
    {
        return $this;
    }
}

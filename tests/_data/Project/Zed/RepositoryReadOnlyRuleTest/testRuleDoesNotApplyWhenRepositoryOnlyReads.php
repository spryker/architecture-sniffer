<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo\Persistence;

class FooRepository
{
    /**
     * @return array
     */
    public function findFoo(): array
    {
        return $this->getFactory()->createQuery()->find();
    }

    /**
     * @return \Pyz\Zed\Foo\Persistence\FooRepository
     */
    protected function getFactory()
    {
        return $this;
    }

    /**
     * @return \Pyz\Zed\Foo\Persistence\FooRepository
     */
    protected function createQuery()
    {
        return $this;
    }

    /**
     * @return array
     */
    protected function find(): array
    {
        return [];
    }
}

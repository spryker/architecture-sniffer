<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo\Business\Model;

use Pyz\Zed\Foo\Persistence\FooRepositoryInterface;

class FooReader
{
    public function read(FooRepositoryInterface $fooRepository): void
    {
        $fooRepository->find();
    }
}

namespace Pyz\Zed\Foo\Persistence;

interface FooRepositoryInterface
{
    /**
     * @return array
     */
    public function find();
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo\Business\Model;

use Orm\Zed\Foo\Persistence\PyzFooQuery;

class FooReader
{
    /**
     * @param \Orm\Zed\Foo\Persistence\PyzFooQuery $fooQuery
     *
     * @return void
     */
    public function read(PyzFooQuery $fooQuery): void
    {
        $fooQuery->find();
    }
}

namespace Orm\Zed\Foo\Persistence;

class PyzFooQuery
{
    /**
     * @return array
     */
    public function find()
    {
        return [];
    }
}

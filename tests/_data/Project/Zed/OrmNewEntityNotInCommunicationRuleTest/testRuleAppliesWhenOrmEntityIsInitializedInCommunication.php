<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo\Communication\Controller;

use Orm\Zed\Foo\Persistence\PyzFoo;

class FooController
{
    /**
     * @return \Orm\Zed\Foo\Persistence\PyzFoo
     */
    public function createAction()
    {
        return new PyzFoo();
    }
}

namespace Orm\Zed\Foo\Persistence;

class PyzFoo
{
}

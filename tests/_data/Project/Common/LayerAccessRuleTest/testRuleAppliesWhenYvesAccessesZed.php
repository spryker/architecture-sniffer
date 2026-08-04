<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\Foo;

use Pyz\Zed\Foo\Business\FooFacade;

class FooYvesController
{
    public function indexAction(FooFacade $fooFacade): void
    {
        $fooFacade->doSomething();
    }
}

namespace Pyz\Zed\Foo\Business;

class FooFacade
{
    public function doSomething(): void
    {
    }
}

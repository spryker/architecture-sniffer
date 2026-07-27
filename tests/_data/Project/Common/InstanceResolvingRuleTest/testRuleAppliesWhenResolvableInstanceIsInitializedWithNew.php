<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo\Business;

use Pyz\Client\Foo\FooClient;

class FooBusinessConsumer
{
    public function doSomething(): void
    {
        $client = new FooClient();
    }
}

namespace Pyz\Client\Foo;

class FooClient
{
}

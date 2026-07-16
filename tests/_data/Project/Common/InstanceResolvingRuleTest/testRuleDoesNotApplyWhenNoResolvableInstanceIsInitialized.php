<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo\Business;

use Pyz\Zed\Foo\Business\Model\PlainValue;

class FooBusinessConsumer
{
    /**
     * @return void
     */
    public function doSomething(): void
    {
        $value = new PlainValue();
    }
}

namespace Pyz\Zed\Foo\Business\Model;

class PlainValue
{
}

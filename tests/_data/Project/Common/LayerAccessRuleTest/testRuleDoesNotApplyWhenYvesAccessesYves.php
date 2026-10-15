<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\Foo;

use Pyz\Yves\Foo\Reader\FooReader;

class FooYvesController
{
    public function indexAction(FooReader $fooReader): void
    {
        $fooReader->read();
    }
}

namespace Pyz\Yves\Foo\Reader;

class FooReader
{
    public function read(): void
    {
    }
}

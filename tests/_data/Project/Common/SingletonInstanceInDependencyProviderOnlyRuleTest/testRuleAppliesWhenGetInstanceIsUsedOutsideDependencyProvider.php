<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo\Business;

use Pyz\Zed\Foo\Business\Model\Singleton;

class FooBusinessFactory
{
    /**
     * @return \Pyz\Zed\Foo\Business\Model\Singleton
     */
    public function createSingleton()
    {
        return Singleton::getInstance();
    }
}

namespace Pyz\Zed\Foo\Business\Model;

class Singleton
{
    /**
     * @return \Pyz\Zed\Foo\Business\Model\Singleton
     */
    public static function getInstance()
    {
        return new static();
    }
}

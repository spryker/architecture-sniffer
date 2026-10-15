<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo;

use Pyz\Zed\Foo\Business\Model\Singleton;

class FooDependencyProvider
{
    /**
     * @return \Pyz\Zed\Foo\Business\Model\Singleton
     */
    public function provideSingleton()
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Foo;

class FooDependencyProvider
{
    public function provideBusinessLayerDependencies(): void
    {
        $this->getLocator()->bar()->facade();
    }

    /**
     * @return \Pyz\Zed\Foo\FooDependencyProvider
     */
    protected function getLocator()
    {
        return $this;
    }

    /**
     * @return \Pyz\Zed\Foo\FooDependencyProvider
     */
    protected function bar()
    {
        return $this;
    }

    /**
     * @return \Pyz\Zed\Foo\FooDependencyProvider
     */
    protected function facade()
    {
        return $this;
    }
}

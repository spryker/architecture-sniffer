<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Client\Foo;

class FooDependencyProvider
{
    /**
     * @return void
     */
    public function provideServiceLayerDependencies(): void
    {
        $this->getProvidedDependency()->zedRequest();
    }

    /**
     * @return \Pyz\Client\Foo\FooDependencyProvider
     */
    protected function getProvidedDependency()
    {
        return $this;
    }
}

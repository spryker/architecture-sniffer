<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Module\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class ZedBusinessFactory extends AbstractBusinessFactory
{
    public function createSomethingWithTwoNewStatements()
    {
        if (rand(0, 1)) {
            return new \stdClass();
        }

        return new \stdClass();
    }
}

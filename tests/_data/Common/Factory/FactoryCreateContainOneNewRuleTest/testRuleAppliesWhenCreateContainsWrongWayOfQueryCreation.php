<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Module\Business\TestRuleAppliesWhenCreateContainsWrongWayOfQueryCreation;

use Orm\Zed\Something\Persistence\SpySomethingQuery;

class WrongWayPropelQueryBusinessFactory
{
    /**
     * @return \Orm\Zed\Something\Persistence\SpySomethingQuery
     */
    public function createWrongWayPropelQuery()
    {
        return new SpySomethingQuery();
    }
}

namespace Orm\Zed\Something\Persistence;

class SpySomethingQuery
{
}

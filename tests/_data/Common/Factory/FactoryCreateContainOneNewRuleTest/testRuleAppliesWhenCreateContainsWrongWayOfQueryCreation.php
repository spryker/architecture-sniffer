<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Module\Business\TestRuleAppliesWhenCreateContainsWrongWayOfQueryCreation;

use Propel\Runtime\ActiveQuery\SpySomethingQuery;

class WrongWayPropelQueryBusinessFactory
{
    /**
     * @return \Propel\Runtime\ActiveQuery\SpySomethingQuery
     */
    public function createWrongWayPropelQuery()
    {
        return new SpySomethingQuery();
    }
}

namespace Propel\Runtime\ActiveQuery;

class ModelCriteria
{
}

class SpySomethingQuery extends ModelCriteria
{
}

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Method\ExternalMethodExtensionReturnTypeRuleTest\TestRuleDoesNotApplyWhenClassExtendInternalAndDoesNotHaveReturnType;

use Spryker\TestRuleDoesNotApplyWhenClassExtendInternalAndDoesNotHaveReturnType\Bar;
use Spryker\Zed\Module\Business\ModuleFacadeInterface;
use Spryker\Zed\Module\Business;

class Foo extends Bar
{
    /**
     * @return bool
     */
    public function foo()
    {
    }
}

namespace Spryker\TestRuleDoesNotApplyWhenClassExtendInternalAndDoesNotHaveReturnType;

class Bar
{
    /**
     * @return bool
     */
    public function foo()
    {
    }
}

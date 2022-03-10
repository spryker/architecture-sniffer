<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Method\ExternalMethodExtensionReturnTypeRuleTest\TestRuleAppliesWhenClassImplementsExternalAndDoesNotHaveReturnType;

use Spryker\Zed\Module\Business\ModuleFacadeInterface;
use Spryker\Zed\Module\Business;
use TestRuleAppliesWhenClassImplementsExternalAndDoesNotHaveReturnType\Bar;

class Foo implements Bar
{
    /**
     * @return \TestRuleAppliesWhenClassImplementsExternalAndDoesNotHaveReturnType\Bar
     */
    public function foo()
    {
    }
}

namespace TestRuleAppliesWhenClassImplementsExternalAndDoesNotHaveReturnType;

interface Bar
{
    /**
     * @return \TestRuleAppliesWhenClassImplementsExternalAndDoesNotHaveReturnType\Bar
     */
    public function foo();
}

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Method\ExternalMethodExtensionReturnTypeRuleTest\TestRuleAppliesWhenClassExtendsExternalAndDoesNotHaveReturnType;

use Spryker\Zed\Module\Business\ModuleFacadeInterface;
use Spryker\Zed\Module\Business;
use TestRuleAppliesWhenClassExtendsExternalAndDoesNotHaveReturnType\Bar;

class Foo extends Bar
{
    /**
     * @return \TestRuleAppliesWhenClassExtendsExternalAndDoesNotHaveReturnType\Bar
     */
    public function foo()
    {
        return new Bar();
    }
}

namespace TestRuleAppliesWhenClassExtendsExternalAndDoesNotHaveReturnType;

class Bar
{
    /**
     * @return \TestRuleAppliesWhenClassExtendsExternalAndDoesNotHaveReturnType\Bar
     */
    public function foo()
    {
    }
}

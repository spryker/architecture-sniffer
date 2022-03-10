<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Method\ExternalMethodExtensionReturnTypeRuleTest\TestRuleDoesNotApplyWhenClassExtendsExternalAndDoesNotHaveUnsupportedReturnTypetestRuleDoesNotApplyWhenClassExtendsExternalAndDoesNotHaveUnsupportedReturnType;

use TestRuleDoesNotApplyWhenClassExtendsExternalAndDoesNotHaveUnsupportedReturnType\Bar;
use Spryker\Zed\Module\Business\ModuleFacadeInterface;
use Spryker\Zed\Module\Business;

class Foo extends Bar
{
    /**
     * @return static
     */
    public function foo()
    {
        return new Foo();
    }
}

namespace TestRuleDoesNotApplyWhenClassExtendsExternalAndDoesNotHaveUnsupportedReturnType;

class Bar
{
    /**
     * @return static
     */
    public function foo()
    {
    }
}

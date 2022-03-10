<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Method\ExternalMethodExtensionReturnTypeRuleTest\TestRuleDoesNotApplyWhenClassExtendsExternalAndHasReturnType;

use TestRuleDoesNotApplyWhenClassExtendsExternalAndHasReturnType\Bar;
use Spryker\Zed\Module\Business\ModuleFacadeInterface;
use Spryker\Zed\Module\Business;

class Foo extends Bar
{
    public function foo(): Bar
    {
        return new Bar();
    }
}

namespace TestRuleDoesNotApplyWhenClassExtendsExternalAndHasReturnType;


class Bar
{
    public function foo()
    {
    }
}

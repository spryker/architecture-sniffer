<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Method\ExternalMethodExtensionReturnTypeRuleTest\TestRuleDoesNotApplyWhenClassImplementsExternalAndHasReturnType;

use TestRuleDoesNotApplyWhenClassImplementsExternalAndHasReturnType\Bar;
use Spryker\Zed\Module\Business\ModuleFacadeInterface;
use Spryker\Zed\Module\Business;

class Foo implements Bar
{
    public function foo(): \stdClass
    {
        return new \stdClass();
    }
}

namespace TestRuleDoesNotApplyWhenClassImplementsExternalAndHasReturnType;

interface Bar
{
    public function foo();
}

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Method\ExternalMethodExtensionReturnTypeRuleTest\TestRuleDoesNotApplyWhenClassDoesNotExtendOrImplementExternal;

use Spryker\Zed\Module\Business\ModuleFacadeInterface;
use TestRuleDoesNotApplyWhenClassDoesNotExtendOrImplementExternal\Bar;
use TestRuleDoesNotApplyWhenClassDoesNotExtendOrImplementExternal\Baz;

class Foo extends Bar implements Baz
{
    /**
     * @return \TestRuleDoesNotApplyWhenClassDoesNotExtendOrImplementExternal\Bar
     */
    public function foo()
    {
        return new Bar();
    }
}

namespace TestRuleDoesNotApplyWhenClassDoesNotExtendOrImplementExternal;

class Bar
{
}

interface Baz
{
}

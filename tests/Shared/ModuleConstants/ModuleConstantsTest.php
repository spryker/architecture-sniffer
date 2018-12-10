<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Shared\ModuleConstants;

use ArchitectureSniffer\Shared\ModuleConstantsFormingConstantValuesRule;
use ArchitectureSniffer\Shared\ModuleConstantsPathRule;
use ArchitectureSniffer\Shared\ModuleConstantsTypeRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class ModuleConstantsTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenModuleConstantsLiesNotInSharedDirectory(): void
    {
        $moduleConstantsPathRule = new ModuleConstantsPathRule();
        $moduleConstantsPathRule->setReport($this->getReportMock(1));
        $moduleConstantsPathRule->apply($this->getInterfaceNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenModuleConstantsIsNotInterface(): void
    {
        $moduleConstantsTypeRule = new ModuleConstantsTypeRule();
        $moduleConstantsTypeRule->setReport($this->getReportMock(1));
        $moduleConstantsTypeRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenModuleConstantsHasCorrectConstantValues(): void
    {
        $moduleConstantsFormingConstantValuesRule = new ModuleConstantsFormingConstantValuesRule();
        $moduleConstantsFormingConstantValuesRule->setReport($this->getReportMock(0));
        $moduleConstantsFormingConstantValuesRule->apply($this->getInterfaceNode());
    }
}

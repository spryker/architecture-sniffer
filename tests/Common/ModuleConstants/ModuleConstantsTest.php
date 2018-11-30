<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\ModuleConstants;

use ArchitectureSniffer\Shared\ModuleConstantsIncorrectConstantValuesRule;
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
        $pluginInterfaceSuffixRule = new ModuleConstantsPathRule();
        $pluginInterfaceSuffixRule->setReport($this->getReportMock(1));
        $pluginInterfaceSuffixRule->apply($this->getInterfaceNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenModuleConstantsIsNotInterface(): void
    {
        $pluginInterfaceSuffixRule = new ModuleConstantsTypeRule();
        $pluginInterfaceSuffixRule->setReport($this->getReportMock(1));
        $pluginInterfaceSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenModuleConstantsHasCorrectConstantValues(): void
    {
        $pluginInterfaceSuffixRule = new ModuleConstantsIncorrectConstantValuesRule();
        $pluginInterfaceSuffixRule->setReport($this->getReportMock(0));
        $pluginInterfaceSuffixRule->apply($this->getInterfaceNode());
    }
}

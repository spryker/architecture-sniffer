<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Plugin;

use ArchitectureSniffer\Common\Plugin\NewPluginExtensionModuleRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class NewPluginExtensionModuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenPluginImplementsNotExtensionModuleInterface(): void
    {
        $pluginSuffixRule = new NewPluginExtensionModuleRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenPluginImplementsExtensionModuleInterface(): void
    {
        $pluginSuffixRule = new NewPluginExtensionModuleRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }
}

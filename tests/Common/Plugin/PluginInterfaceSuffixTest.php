<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Plugin;

use ArchitectureSniffer\Common\Plugin\PluginInterfaceSuffixRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class PluginInterfaceSuffixTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenInPluginDirectoryAndPluginInterfaceSuffixIsMissing(): void
    {
        $pluginInterfaceSuffixRule = new PluginInterfaceSuffixRule();
        $pluginInterfaceSuffixRule->setReport($this->getReportMock(1));
        $pluginInterfaceSuffixRule->apply($this->getInterfaceNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenNotAPluginInterface(): void
    {
        $pluginInterfaceSuffixRule = new PluginInterfaceSuffixRule();
        $pluginInterfaceSuffixRule->setReport($this->getReportMock(0));
        $pluginInterfaceSuffixRule->apply($this->getInterfaceNode());
    }
}

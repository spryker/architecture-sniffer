<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Zed\Communication\Plugin;

use ArchitectureSniffer\Zed\Communication\Plugin\PluginArgumentsNotAllowedUseEntityTransferRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class PluginArgumentsNotAllowedUseEntityTransferRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testPluginMethodHasEntityTransferArgument(): void
    {
        $pluginSuffixRule = new PluginArgumentsNotAllowedUseEntityTransferRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testPluginMethodDoesNotHaveEntityTransferArgument(): void
    {
        $pluginSuffixRule = new PluginArgumentsNotAllowedUseEntityTransferRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }
}

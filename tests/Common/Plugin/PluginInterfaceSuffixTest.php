<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

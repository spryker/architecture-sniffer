<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

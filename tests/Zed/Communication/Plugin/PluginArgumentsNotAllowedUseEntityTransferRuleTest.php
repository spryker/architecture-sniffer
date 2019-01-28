<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

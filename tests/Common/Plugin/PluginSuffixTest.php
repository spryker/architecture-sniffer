<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Plugin;

use ArchitectureSniffer\Common\Plugin\PluginSuffixRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class PluginSuffixTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenExtendsAbstractPluginAndSuffixIsMissing(): void
    {
        $pluginSuffixRule = new PluginSuffixRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesForPluginTestAndSuffixIsMissing(): void
    {
        $pluginSuffixRule = new PluginSuffixRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenInPluginDirectoryAndPluginSuffixIsMissing(): void
    {
        $pluginSuffixRule = new PluginSuffixRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenNotAPlugin(): void
    {
        $pluginSuffixRule = new PluginSuffixRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenPluginSuffixIsNotMissing(): void
    {
        $pluginSuffixRule = new PluginSuffixRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenPluginImplementsServiceProviderInterface(): void
    {
        $pluginSuffixRule = new PluginSuffixRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenTestPluginSuffixIsNotMissing(): void
    {
        $pluginSuffixRule = new PluginSuffixRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }
}

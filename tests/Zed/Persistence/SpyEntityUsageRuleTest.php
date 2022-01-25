<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Zed\Persistence;

use ArchitectureSniffer\Zed\Persistence\SpyEntityUsageRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class SpyEntityUsageRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRepositoryCanCreateSpyEntity(): void
    {
        $pluginSuffixRule = new SpyEntityUsageRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testEntityManagerCanCreateSpyEntity(): void
    {
        $pluginSuffixRule = new SpyEntityUsageRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testExcludedModuleCanCreateSpyEntity(): void
    {
        $pluginSuffixRule = new SpyEntityUsageRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testNotZedLevelCanCreateSpyEntity(): void
    {
        $pluginSuffixRule = new SpyEntityUsageRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testDeprecatedClassCanCreateSpyEntity(): void
    {
        $pluginSuffixRule = new SpyEntityUsageRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testFacadeCanCreateSpyEntityInDeprecatedMethod(): void
    {
        $pluginSuffixRule = new SpyEntityUsageRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testFacadeCanNotCreateSpyEntity(): void
    {
        $pluginSuffixRule = new SpyEntityUsageRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }
}

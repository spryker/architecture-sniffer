<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Factory;

use ArchitectureSniffer\Common\Factory\FactoryCreateContainOneNewRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class FactoryCreateContainOneNewRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenCreateContainsOneNewStatement(): void
    {
        $pluginSuffixRule = new FactoryCreateContainOneNewRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenCreateNewSpyQueryViaCreateMethod(): void
    {
        $pluginSuffixRule = new FactoryCreateContainOneNewRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenMethodDoesNotStartWithCreatePrefix(): void
    {
        $pluginSuffixRule = new FactoryCreateContainOneNewRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenMethodNameContainsCollectorQuery(): void
    {
        $pluginSuffixRule = new FactoryCreateContainOneNewRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenCreateDoesNotContainNewStatement(): void
    {
        $pluginSuffixRule = new FactoryCreateContainOneNewRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenMethodNameContainsCollectorQueryButWithoutNewStatement(): void
    {
        $pluginSuffixRule = new FactoryCreateContainOneNewRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenCreateContainsTwoNewStatements(): void
    {
        $pluginSuffixRule = new FactoryCreateContainOneNewRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenCreateContainsWrongWayOfQueryCreation(): void
    {
        $pluginSuffixRule = new FactoryCreateContainOneNewRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertIsString((new FactoryCreateContainOneNewRule())->getDescription());
    }
}

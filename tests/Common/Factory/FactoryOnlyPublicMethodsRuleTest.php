<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Factory;

use ArchitectureSniffer\Common\Factory\FactoryOnlyPublicMethodsRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class FactoryOnlyPublicMethodsRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenMethodIsProtected(): void
    {
        $this->setTestFile('zedBusinessFactoryTest.php');
        $pluginSuffixRule = new FactoryOnlyPublicMethodsRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenMethodIsPrivate(): void
    {
        $this->setTestFile('zedBusinessFactoryTest.php');
        $pluginSuffixRule = new FactoryOnlyPublicMethodsRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getMethodNode(1));
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenMethodIsPublic(): void
    {
        $this->setTestFile('zedBusinessFactoryTest.php');
        $pluginSuffixRule = new FactoryOnlyPublicMethodsRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getMethodNode(2));
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenNodeNotFactory(): void
    {
        $pluginSuffixRule = new FactoryOnlyPublicMethodsRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getMethodNode());
    }
}

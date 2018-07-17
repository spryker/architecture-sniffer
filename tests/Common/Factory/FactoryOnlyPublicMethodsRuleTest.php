<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
        $this->setTestFile('zedBusinessFactory.php');
        $pluginSuffixRule = new FactoryOnlyPublicMethodsRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenMethodIsPrivate(): void
    {
        $this->setTestFile('zedBusinessFactory.php');
        $pluginSuffixRule = new FactoryOnlyPublicMethodsRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getMethodNode(1));
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenMethodIsPublic(): void
    {
        $this->setTestFile('zedBusinessFactory.php');
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

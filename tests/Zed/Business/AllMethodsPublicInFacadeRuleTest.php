<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Zed\Business;

use ArchitectureSniffer\Zed\Business\Facade\AllMethodsPublicInFacadeRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class AllMethodsPublicInFacadeRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyForDeprecatedMethod(): void
    {
        $pluginSuffixRule = new AllMethodsPublicInFacadeRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyForAbstractFacade(): void
    {
        $pluginSuffixRule = new AllMethodsPublicInFacadeRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyForPublicMethod(): void
    {
        $pluginSuffixRule = new AllMethodsPublicInFacadeRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyForNotFacadeMethod(): void
    {
        $pluginSuffixRule = new AllMethodsPublicInFacadeRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleApplyForProtectedMethod(): void
    {
        $pluginSuffixRule = new AllMethodsPublicInFacadeRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleApplyForPrivateMethod(): void
    {
        $pluginSuffixRule = new AllMethodsPublicInFacadeRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertIsString((new AllMethodsPublicInFacadeRule())->getDescription());
    }
}

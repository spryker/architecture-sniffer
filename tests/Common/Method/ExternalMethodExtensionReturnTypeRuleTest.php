<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Method;

use ArchitectureSniffer\Common\Method\ExternalMethodExtensionReturnTypeRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class ExternalMethodExtensionReturnTypeRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassExtendInternalAndDoesNotHaveReturnType(): void
    {
        $bridgePathRule = new ExternalMethodExtensionReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassDoesNotExtendOrImplementExternal(): void
    {
        $bridgePathRule = new ExternalMethodExtensionReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassExtendsExternalAndHasReturnType(): void
    {
        $bridgePathRule = new ExternalMethodExtensionReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassImplementsExternalAndHasReturnType(): void
    {
        $bridgePathRule = new ExternalMethodExtensionReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassExtendsExternalAndDoesNotHaveUnsupportedReturnType(): void
    {
        $bridgePathRule = new ExternalMethodExtensionReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenClassExtendsExternalAndDoesNotHaveReturnType(): void
    {
        $bridgePathRule = new ExternalMethodExtensionReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(1));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenClassImplementsExternalAndDoesNotHaveReturnType(): void
    {
        $bridgePathRule = new ExternalMethodExtensionReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(1));
        $bridgePathRule->apply($this->getClassNode());
    }
}

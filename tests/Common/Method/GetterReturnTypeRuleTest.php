<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Method;

use ArchitectureSniffer\Common\Method\GetterReturnTypeRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class GetterReturnTypeRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenNotBoolScalarReturnTypeSpecified(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenMixedReturnTypeSpecified(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassReturnTypeSpecified(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenNotBoolScalarReturnTypeSpecifiedInPhpDoc(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassReturnTypeSpecifiedInPhpDoc(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenBoolReturnTypeSpecifiedForNotGetter(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenInheritDocBlockExists(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenBoolReturnTypeSpecified(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(1));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenBoolReturnTypeSpecifiedInPhpDoc(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(1));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenReturnTypeNotSpecified(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(1));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenVoidReturnTypeSpecified(): void
    {
        $bridgePathRule = new GetterReturnTypeRule();
        $bridgePathRule->setReport($this->getReportMock(1));
        $bridgePathRule->apply($this->getClassNode());
    }
}

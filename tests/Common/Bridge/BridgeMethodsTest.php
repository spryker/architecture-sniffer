<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Bridge;

use ArchitectureSniffer\Common\Bridge\BridgeMethodsRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class BridgeMethodsTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenBridgeMethodsAreCorrect(): void
    {
        $bridgeMethodsRule = new BridgeMethodsRule();
        $bridgeMethodsRule->setReport($this->getReportMock(0));
        $bridgeMethodsRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenBridgeMethodsAreNotCorrect(): void
    {
        $bridgeMethodsRule = new BridgeMethodsRule();
        $bridgeMethodsRule->setReport($this->getReportMock(1));
        $bridgeMethodsRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenBridgeMethodsParamsShouldHaveTypeHint(): void
    {
        $bridgeMethodsRule = new BridgeMethodsRule();
        $bridgeMethodsRule->setReport($this->getReportMock(1));
        $bridgeMethodsRule->apply($this->getClassNode());
    }
}

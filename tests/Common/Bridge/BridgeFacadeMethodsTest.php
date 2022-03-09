<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Bridge;

use ArchitectureSniffer\Common\Bridge\BridgeFacadeMethodsRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class BridgeFacadeMethodsTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenBridgeFacadeMethodsAreNotCorrect(): void
    {
        $bridgeMethodsRule = new BridgeFacadeMethodsRule();
        $bridgeMethodsRule->setReport($this->getReportMock(3));
        $bridgeMethodsRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenFacadeBridgeMethodsAreCorrect(): void
    {
        $bridgeMethodsRule = new BridgeFacadeMethodsRule();
        $bridgeMethodsRule->setReport($this->getReportMock(0));
        $bridgeMethodsRule->apply($this->getClassNode());
    }
}

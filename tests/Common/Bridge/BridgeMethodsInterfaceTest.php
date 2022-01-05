<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Bridge;

use ArchitectureSniffer\Common\Bridge\BridgeMethodsInterfaceRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class BridgeMethodsInterfaceTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenBridgeInterfaceMethodsAreCorrect(): void
    {
        $bridgeMethodsInterfaceRule = new BridgeMethodsInterfaceRule();
        $bridgeMethodsInterfaceRule->setReport($this->getReportMock(0));
        $bridgeMethodsInterfaceRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenBridgeInterfaceMethodsAreNotCorrect(): void
    {
        $bridgeMethodsInterfaceRule = new BridgeMethodsInterfaceRule();
        $bridgeMethodsInterfaceRule->setReport($this->getReportMock(1));
        $bridgeMethodsInterfaceRule->apply($this->getClassNode());
    }
}

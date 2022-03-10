<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Bridge;

use ArchitectureSniffer\Common\Bridge\BridgePathRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class BridgePathTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenBridgePathIsCorrect(): void
    {
        $bridgePathRule = new BridgePathRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenBridgePathIsNotCorrect(): void
    {
        $bridgePathRule = new BridgePathRule();
        $bridgePathRule->setReport($this->getReportMock(2));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertIsString((new BridgePathRule())->getDescription());
    }
}

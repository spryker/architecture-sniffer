<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
}

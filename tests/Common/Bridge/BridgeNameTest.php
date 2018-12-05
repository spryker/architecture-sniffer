<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Bridge;

use ArchitectureSniffer\Common\Bridge\BridgeNameRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class BridgeNameTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenBridgeNameIsCorrect(): void
    {
        $bridgeNameRule = new BridgeNameRule();
        $bridgeNameRule->setReport($this->getReportMock(0));
        $bridgeNameRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenBridgeNameIsNotCorrect(): void
    {
        $bridgeNameRule = new BridgeNameRule();
        $bridgeNameRule->setReport($this->getReportMock(2));
        $bridgeNameRule->apply($this->getClassNode());
    }
}

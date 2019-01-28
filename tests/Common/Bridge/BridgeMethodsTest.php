<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
        $bridgeMethodsRule->setReport($this->getReportMock(2));
        $bridgeMethodsRule->apply($this->getClassNode());
    }
}

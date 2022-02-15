<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Bridge;

use ArchitectureSniffer\Common\Bridge\BridgeMethodsRule;
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

    /**
     * @return void
     */
    public function testGetDescriptionReturnsString(): void
    {
        $this->assertIsString((new BridgeNameRule())->getDescription());
    }
}

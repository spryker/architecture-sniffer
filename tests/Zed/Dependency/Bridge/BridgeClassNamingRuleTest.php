<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Zed\Dependency\Bridge;

use ArchitectureSniffer\Zed\Dependency\Bridge\BridgeClassNamingRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class BridgeClassNamingRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testBridgeClassNameDoesMatch(): void
    {
        $pluginSuffixRule = new BridgeClassNamingRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testBridgeClassNameDoesNotMatch(): void
    {
        $pluginSuffixRule = new BridgeClassNamingRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }
}

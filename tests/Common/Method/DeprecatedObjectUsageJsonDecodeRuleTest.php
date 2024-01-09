<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Method;

use ArchitectureSniffer\Common\Method\DeprecatedObjectUsageJsonDecodeRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class DeprecatedObjectUsageJsonDecodeRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenUsageCorrect(): void
    {
        $bridgePathRule = new DeprecatedObjectUsageJsonDecodeRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleApplyWhenUsageIncorrect(): void
    {
        $bridgePathRule = new DeprecatedObjectUsageJsonDecodeRule();
        $bridgePathRule->setReport($this->getReportMock(2));
        $bridgePathRule->apply($this->getClassNode());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Method;

use ArchitectureSniffer\Common\Method\ExistsPostfixRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class ExistsPostfixRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenPostfixCorrect(): void
    {
        $bridgePathRule = new ExistsPostfixRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenWordExistingUsed(): void
    {
        $bridgePathRule = new ExistsPostfixRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWithoutForbiddenPrefix(): void
    {
        $bridgePathRule = new ExistsPostfixRule();
        $bridgePathRule->setReport($this->getReportMock(0));
        $bridgePathRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleAppliesWhenForbiddenPrefixPresentAndPostfixIncorrect(): void
    {
        $bridgePathRule = new ExistsPostfixRule();
        $bridgePathRule->setReport($this->getReportMock(3));
        $bridgePathRule->apply($this->getClassNode());
    }
}

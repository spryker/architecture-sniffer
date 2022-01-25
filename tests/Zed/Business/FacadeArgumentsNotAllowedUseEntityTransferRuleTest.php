<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Zed\Business;

use ArchitectureSniffer\Zed\Business\Facade\FacadeArgumentsNotAllowedUseEntityTransferRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class FacadeArgumentsNotAllowedUseEntityTransferRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testFacadeMethodHasEntityTransferArgument(): void
    {
        $pluginSuffixRule = new FacadeArgumentsNotAllowedUseEntityTransferRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testFacadeDeprecatedMethodHasEntityTransferArgument(): void
    {
        $pluginSuffixRule = new FacadeArgumentsNotAllowedUseEntityTransferRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testFacadeMethodDoesNotHaveEntityTransferArgument(): void
    {
        $pluginSuffixRule = new FacadeArgumentsNotAllowedUseEntityTransferRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getMethodNode());
    }
}

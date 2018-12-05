<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public function testFacadeMethodDoesNotHaveEntityTransferArgument(): void
    {
        $pluginSuffixRule = new FacadeArgumentsNotAllowedUseEntityTransferRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getMethodNode());
    }
}

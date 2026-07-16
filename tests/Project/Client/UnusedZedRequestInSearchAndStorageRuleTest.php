<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Client;

use ArchitectureSniffer\Project\Client\UnusedZedRequestInSearchAndStorageRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class UnusedZedRequestInSearchAndStorageRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenZedRequestIsUsedInStorageDependencyProvider(): void
    {
        $rule = new UnusedZedRequestInSearchAndStorageRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenZedRequestIsUsedInRegularDependencyProvider(): void
    {
        $rule = new UnusedZedRequestInSearchAndStorageRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertIsString((new UnusedZedRequestInSearchAndStorageRule())->getDescription());
    }
}

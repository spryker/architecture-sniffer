<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Common;

use ArchitectureSniffer\Project\Common\ProjectNoBridgeRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class ProjectNoBridgeRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenClassIsABridge(): void
    {
        $rule = new ProjectNoBridgeRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassIsNotABridge(): void
    {
        $rule = new ProjectNoBridgeRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertIsString((new ProjectNoBridgeRule())->getDescription());
    }
}

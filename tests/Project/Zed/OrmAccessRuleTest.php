<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Zed;

use ArchitectureSniffer\Project\Zed\OrmAccessRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class OrmAccessRuleTest extends AbstractArchitectureSnifferRuleTest
{
    public function testRuleAppliesWhenZedBusinessAccessesOrmQuery(): void
    {
        $rule = new OrmAccessRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenZedBusinessDoesNotAccessOrm(): void
    {
        $rule = new OrmAccessRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString((new OrmAccessRule())->getDescription());
    }
}

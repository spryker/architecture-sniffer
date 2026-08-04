<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Zed;

use ArchitectureSniffer\Project\Zed\RestrictedOrmQueryAccessInZedPersistenceRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class RestrictedOrmQueryAccessInZedPersistenceRuleTest extends AbstractArchitectureSnifferRuleTest
{
    public function testRuleAppliesWhenNonAllowedPersistenceClassAccessesOrmQuery(): void
    {
        $rule = new RestrictedOrmQueryAccessInZedPersistenceRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenRepositoryAccessesOrmQuery(): void
    {
        $rule = new RestrictedOrmQueryAccessInZedPersistenceRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString((new RestrictedOrmQueryAccessInZedPersistenceRule())->getDescription());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Zed;

use ArchitectureSniffer\Project\Zed\OrmNewEntityNotInCommunicationRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class OrmNewEntityNotInCommunicationRuleTest extends AbstractArchitectureSnifferRuleTest
{
    public function testRuleAppliesWhenOrmEntityIsInitializedInCommunication(): void
    {
        $rule = new OrmNewEntityNotInCommunicationRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenOrmEntityIsInitializedOutsideCommunication(): void
    {
        $rule = new OrmNewEntityNotInCommunicationRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString((new OrmNewEntityNotInCommunicationRule())->getDescription());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Common;

use ArchitectureSniffer\Project\Common\LocatorInDependencyProviderOnlyRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class LocatorInDependencyProviderOnlyRuleTest extends AbstractArchitectureSnifferRuleTest
{
    public function testRuleAppliesWhenLocatorIsUsedOutsideDependencyProvider(): void
    {
        $rule = new LocatorInDependencyProviderOnlyRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getMethodNode());
    }

    public function testRuleDoesNotApplyWhenLocatorIsUsedInDependencyProvider(): void
    {
        $rule = new LocatorInDependencyProviderOnlyRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getMethodNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString((new LocatorInDependencyProviderOnlyRule())->getDescription());
    }
}

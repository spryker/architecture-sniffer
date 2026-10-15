<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Common;

use ArchitectureSniffer\Project\Common\SingletonInstanceInDependencyProviderOnlyRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class SingletonInstanceInDependencyProviderOnlyRuleTest extends AbstractArchitectureSnifferRuleTest
{
    public function testRuleAppliesWhenGetInstanceIsUsedOutsideDependencyProvider(): void
    {
        $rule = new SingletonInstanceInDependencyProviderOnlyRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getMethodNode());
    }

    public function testRuleDoesNotApplyWhenGetInstanceIsUsedInDependencyProvider(): void
    {
        $rule = new SingletonInstanceInDependencyProviderOnlyRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getMethodNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString((new SingletonInstanceInDependencyProviderOnlyRule())->getDescription());
    }
}

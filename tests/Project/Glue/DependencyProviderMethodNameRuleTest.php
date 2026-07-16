<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Glue;

use ArchitectureSniffer\Glue\DependencyProvider\DependencyProviderMethodNameRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class DependencyProviderMethodNameRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenGlueDependencyProviderHasDisallowedMethodName(): void
    {
        $rule = new DependencyProviderMethodNameRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenGlueDependencyProviderHasGetterMethodName(): void
    {
        $rule = new DependencyProviderMethodNameRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getMethodNode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertIsString((new DependencyProviderMethodNameRule())->getDescription());
    }
}

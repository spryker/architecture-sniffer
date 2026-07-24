<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Common;

use ArchitectureSniffer\Project\Common\DisallowedModuleNameRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class DisallowedModuleNameRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenModuleNameContainsDisallowedWord(): void
    {
        $rule = new DisallowedModuleNameRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenModuleNameIsNormal(): void
    {
        $rule = new DisallowedModuleNameRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertIsString((new DisallowedModuleNameRule())->getDescription());
    }
}

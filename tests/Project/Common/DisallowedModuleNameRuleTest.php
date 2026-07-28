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
    public function testRuleAppliesWhenModuleNameContainsDisallowedWord(): void
    {
        $rule = new DisallowedModuleNameRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenModuleNameIsNormal(): void
    {
        $rule = new DisallowedModuleNameRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenModuleIsInModuleExcludeList(): void
    {
        $this->setTestFile('testRuleAppliesWhenModuleNameContainsDisallowedWord.php');

        $rule = new DisallowedModuleNameRule();
        $rule->addProperty('moduleExcludeList', 'DummyModule');
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenDisallowedWordsDoNotMatchModuleName(): void
    {
        $this->setTestFile('testRuleAppliesWhenModuleNameContainsDisallowedWord.php');

        $rule = new DisallowedModuleNameRule();
        $rule->addProperty('disallowedWords', 'antelope');
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString((new DisallowedModuleNameRule())->getDescription());
    }
}

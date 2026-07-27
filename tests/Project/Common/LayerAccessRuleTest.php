<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Common;

use ArchitectureSniffer\Project\Common\LayerAccessRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class LayerAccessRuleTest extends AbstractArchitectureSnifferRuleTest
{
    public function testRuleAppliesWhenYvesAccessesZed(): void
    {
        $rule = new LayerAccessRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenYvesAccessesYves(): void
    {
        $rule = new LayerAccessRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenAnalyzedClassIsInIgnoreClassPattern(): void
    {
        $this->setTestFile('testRuleAppliesWhenYvesAccessesZed.php');

        $rule = new LayerAccessRule();
        $rule->addProperty('ignoreClassPattern', '(FooYvesController$)');
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenDependencyIsInIgnoreDependencyPattern(): void
    {
        $this->setTestFile('testRuleAppliesWhenYvesAccessesZed.php');

        $rule = new LayerAccessRule();
        $rule->addProperty('ignoreDependencyPattern', '(FooFacade$)');
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString((new LayerAccessRule())->getDescription());
    }
}

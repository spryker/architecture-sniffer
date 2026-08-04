<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Zed;

use ArchitectureSniffer\Project\Zed\RepositoryReadOnlyRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class RepositoryReadOnlyRuleTest extends AbstractArchitectureSnifferRuleTest
{
    public function testRuleAppliesWhenRepositoryUsesWriteOperation(): void
    {
        $rule = new RepositoryReadOnlyRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getMethodNode());
    }

    public function testRuleDoesNotApplyWhenRepositoryOnlyReads(): void
    {
        $rule = new RepositoryReadOnlyRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getMethodNode());
    }

    public function testRuleDoesNotApplyWhenWriteOperationIsNotInRestrictedMethods(): void
    {
        $this->setTestFile('testRuleAppliesWhenRepositoryUsesWriteOperation.php');

        $rule = new RepositoryReadOnlyRule();
        $rule->addProperty('restrictedMethods', 'update,delete');
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getMethodNode());
    }

    public function testRuleDoesNotApplyWhenRepositoryIsInIgnoreClassPattern(): void
    {
        $this->setTestFile('testRuleAppliesWhenRepositoryUsesWriteOperation.php');

        $rule = new RepositoryReadOnlyRule();
        $rule->addProperty('ignoreClassPattern', '(FooRepository$)');
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getMethodNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString((new RepositoryReadOnlyRule())->getDescription());
    }
}

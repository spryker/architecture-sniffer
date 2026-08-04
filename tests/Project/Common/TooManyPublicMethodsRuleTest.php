<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Common;

use ArchitectureSniffer\Project\Common\TooManyPublicMethodsRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class TooManyPublicMethodsRuleTest extends AbstractArchitectureSnifferRuleTest
{
    public function testRuleAppliesWhenClassHasTooManyPublicMethods(): void
    {
        $rule = $this->createRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenClassHasFewPublicMethods(): void
    {
        $rule = $this->createRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString($this->createRule()->getDescription());
    }

    protected function createRule(): TooManyPublicMethodsRule
    {
        $rule = new TooManyPublicMethodsRule();
        $rule->addProperty('maxMethods', '2');
        $rule->addProperty('ignoreClassPattern', '(^$)');
        $rule->addProperty('ignoreMethodPattern', '(^(__.*)$)');

        return $rule;
    }
}

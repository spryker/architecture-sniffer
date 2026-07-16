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
    /**
     * @return void
     */
    public function testRuleAppliesWhenClassHasTooManyPublicMethods(): void
    {
        $rule = $this->createRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassHasFewPublicMethods(): void
    {
        $rule = $this->createRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertIsString($this->createRule()->getDescription());
    }

    /**
     * @return \ArchitectureSniffer\Project\Common\TooManyPublicMethodsRule
     */
    protected function createRule(): TooManyPublicMethodsRule
    {
        $rule = new TooManyPublicMethodsRule();
        $rule->addProperty('maxmethods', '2');
        $rule->addProperty('ignoreclasspattern', '(^$)');
        $rule->addProperty('ignoremethodpattern', '(^(__.*)$)');

        return $rule;
    }
}

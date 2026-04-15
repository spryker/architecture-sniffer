<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Relationship;

use ArchitectureSniffer\Common\Relationship\DirectRelationshipResolverInterfaceUsageRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class DirectRelationshipResolverInterfaceUsageRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRuleAppliesWhenExternalClassImplementsRelationshipResolverInterface(): void
    {
        $rule = new DirectRelationshipResolverInterfaceUsageRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassIsInsideApiPlatformNamespace(): void
    {
        $rule = new DirectRelationshipResolverInterfaceUsageRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassImplementsUnrelatedInterface(): void
    {
        $rule = new DirectRelationshipResolverInterfaceUsageRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testRuleDoesNotApplyWhenClassImplementsNoInterfaces(): void
    {
        $rule = new DirectRelationshipResolverInterfaceUsageRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertIsString((new DirectRelationshipResolverInterfaceUsageRule())->getDescription());
    }
}

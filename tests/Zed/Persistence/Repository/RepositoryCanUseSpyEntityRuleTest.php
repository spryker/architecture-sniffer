<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Zed\Persistence\Repository;

use ArchitectureSniffer\Zed\Persistence\Repository\RepositoryCanUseSpyEntityRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;

class RepositoryCanUseSpyEntityRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @return void
     */
    public function testRepositoryCanCreateSpyEntity(): void
    {
        $pluginSuffixRule = new RepositoryCanUseSpyEntityRule();
        $pluginSuffixRule->setReport($this->getReportMock(0));
        $pluginSuffixRule->apply($this->getClassNode());
    }

    /**
     * @return void
     */
    public function testFacadeCanNotCreateSpyEntity(): void
    {
        $pluginSuffixRule = new RepositoryCanUseSpyEntityRule();
        $pluginSuffixRule->setReport($this->getReportMock(1));
        $pluginSuffixRule->apply($this->getClassNode());
    }
}

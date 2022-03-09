<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Session\Dependency\Facade;

use Generated\Shared\Transfer\TestCollectionResponseTransfer;
use Generated\Shared\Transfer\TestDeleteCriteriaTransfer;
use Spryker\Zed\Test\Facade\SessionFreeToTestFacadeInterface;

class SessionTreeToTestFacadeBridge implements SessionFreeToTestFacadeInterface
{
    /**
     * @param \Spryker\Zed\Test\Facade\TestFacadeInterface $testFacade
     */
    public function __construct($testFacade)
    {
    }

    public function deleteTestCollection(TestDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestCollectionResponseTransfer
    {
    }
}

// Database module

namespace Spryker\Zed\Test\Facade;

use Generated\Shared\Transfer\TestDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestCollectionResponseTransfer;

interface SessionFreeToTestFacadeInterface
{
    public function deleteTestCollection(TestDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestCollectionResponseTransfer;
}

namespace Generated\Shared\Transfer;

class TestDeleteCriteriaTransfer
{

}

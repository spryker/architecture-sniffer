<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Session\Dependency\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestResponseTransfer;
use Spryker\Zed\Test\Facade\SessionOneToTestFacadeInterface;

class SessionOneToTestFacadeBridge implements SessionOneToTestFacadeInterface
{
    /**
     * @param \Spryker\Zed\Test\Facade\TestFacadeInterface $testFacade
     */
    public function __construct($testFacade)
    {
    }

    public function deleteTestCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestResponseTransfer
    {
    }
}

// Database module

namespace Spryker\Zed\Test\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestResponseTransfer;

interface SessionOneToTestFacadeInterface
{
    public function deleteTestCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestResponseTransfer;
}

namespace Generated\Shared\Transfer;

class TestResponseTransfer
{

}

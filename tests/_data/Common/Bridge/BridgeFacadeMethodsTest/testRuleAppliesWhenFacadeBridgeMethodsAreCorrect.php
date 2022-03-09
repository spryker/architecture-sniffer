<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Session\Dependency\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestCollectionResponseTransfer;
use Spryker\Zed\Test\Facade\SessionToTestFacadeInterface;

class SessionToTestFacadeBridge implements SessionToTestFacadeInterface
{
    /**
     * @param \Spryker\Zed\Test\Facade\TestFacadeInterface $testFacade
     */
    public function __construct($testFacade)
    {
    }

    public function deleteTestCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestCollectionResponseTransfer
    {
    }
}

// Database module

namespace Spryker\Zed\Test\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestCollectionResponseTransfer;

interface SessionToTestFacadeInterface
{
    public function deleteTestCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestCollectionResponseTransfer;
}

namespace Generated\Shared\Transfer;

class TestCollectionResponseTransfer
{

}

class TestCollectionDeleteCriteriaTransfer
{

}

namespace Spryker\Zed\Test\Facade;

interface TestFacadeInterface
{
}

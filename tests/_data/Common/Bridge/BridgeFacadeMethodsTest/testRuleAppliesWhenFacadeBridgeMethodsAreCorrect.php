<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Session\Dependency\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestCollectionRequestTransfer;
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

    public function createTestCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer
    {
    }

    public function updateTestCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer
    {
    }
}

// Database module

namespace Spryker\Zed\Test\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestCollectionRequestTransfer;
use Generated\Shared\Transfer\TestCollectionResponseTransfer;

interface SessionToTestFacadeInterface
{
    public function deleteTestCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestCollectionResponseTransfer;
    public function createTestCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer;
    public function updateTestCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer;
}

namespace Generated\Shared\Transfer;

class TestCollectionResponseTransfer
{

}

class TestCollectionRequestTransfer
{

}

class TestCollectionDeleteCriteriaTransfer
{

}

namespace Spryker\Zed\Test\Facade;

interface TestFacadeInterface
{
}

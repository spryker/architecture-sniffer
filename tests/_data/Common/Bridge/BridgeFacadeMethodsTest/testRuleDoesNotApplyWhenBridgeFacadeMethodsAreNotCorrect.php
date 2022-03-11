<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Session\Dependency\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestCollectionRequestTransfer;
use Generated\Shared\Transfer\TestCollectionResponseTransfer;
use Generated\Shared\Transfer\TestDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestListRequestTransfer;
use Generated\Shared\Transfer\TestListResponseTransfer;
use Generated\Shared\Transfer\TestResponseTransfer;
use Spryker\Zed\Test\Facade\SessionTestToTestFacadeInterface;

class SessionTestToTestFacadeBridge implements SessionTestToTestFacadeInterface
{
    /**
     * @param \Spryker\Zed\Test\Facade\TestFacadeInterface $testFacade
     */
    public function __construct($testFacade)
    {
    }

    public function deleteCollection(TestCollectionDeleteCriteriaTransfer $testDeleteCriteriaTransfer): TestCollectionResponseTransfer
    {

    }

    public function deleteTestCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestResponseTransfer
    {
    }

    public function removeTestCollection(TestDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestCollectionResponseTransfer
    {
    }

    public function createCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer
    {
    }

    public function addTestCollection(TestListRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer
    {
    }

    public function createTestReturnCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestListResponseTransfer
    {
    }

    public function createTestModuleCollection(TestListRequestTransfer $testListRequestTransfer): TestCollectionResponseTransfer
    {
    }

    public function saveTestCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer
    {
    }

    public function updateCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer
    {
    }

    public function changeTestCollection(TestListRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer
    {
    }

    public function updateTestReturnCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestListResponseTransfer
    {
    }

    public function updateTestModuleCollection(TestListRequestTransfer $testListRequestTransfer): TestCollectionResponseTransfer
    {
    }
}

// Database module

namespace Spryker\Zed\Test\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestCollectionRequestTransfer;
use Generated\Shared\Transfer\TestCollectionResponseTransfer;
use Generated\Shared\Transfer\TestDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestListRequestTransfer;
use Generated\Shared\Transfer\TestListResponseTransfer;
use Generated\Shared\Transfer\TestResponseTransfer;

interface SessionTestToTestFacadeInterface
{
    public function deleteCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestCollectionResponseTransfer;
    public function deleteTestCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestResponseTransfer;
    public function removeTestCollection(TestDeleteCriteriaTransfer $testDeleteCriteriaTransfer): TestCollectionResponseTransfer;

    public function createCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer;
    public function addTestCollection(TestListRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer;
    public function createTestReturnCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestListResponseTransfer;
    public function createTestModuleCollection(TestListRequestTransfer $testListRequestTransfer): TestCollectionResponseTransfer;
    public function saveTestCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer;

    public function updateCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer;
    public function changeTestCollection(TestListRequestTransfer $testCollectionRequestTransfer): TestCollectionResponseTransfer;
    public function updateTestReturnCollection(TestCollectionRequestTransfer $testCollectionRequestTransfer): TestListResponseTransfer;
    public function updateTestModuleCollection(TestListRequestTransfer $testListRequestTransfer): TestCollectionResponseTransfer;
}


namespace Generated\Shared\Transfer;

class TestListRequestTransfer
{

}
class TestListResponseTransfer
{

}
class TestResponseTransfer
{

}

class TestDeleteCriteriaTransfer
{

}

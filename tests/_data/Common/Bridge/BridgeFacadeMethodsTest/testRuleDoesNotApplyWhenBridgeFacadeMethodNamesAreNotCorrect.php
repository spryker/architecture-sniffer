<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Session\Dependency\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestCollectionResponseTransfer;
use Spryker\Zed\Test\Facade\SessionTestToTestFacadeInterface;

class SessionTestToTestFacadeBridge implements SessionTestToTestFacadeInterface
{
    /**
     * @param \Spryker\Zed\Test\Facade\TestFacadeInterface $testFacade
     */
    public function __construct($testFacade)
    {
    }

    public function deleteCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestCollectionResponseTransfer
    {
    }
}

// Database module

namespace Spryker\Zed\Test\Facade;

use Generated\Shared\Transfer\TestCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestCollectionResponseTransfer;

interface SessionTestToTestFacadeInterface
{
    public function deleteCollection(TestCollectionDeleteCriteriaTransfer $testCollectionDeleteCriteriaTransfer): TestCollectionResponseTransfer;
}

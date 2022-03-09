<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Session\Dependency\Facade;

use Generated\Shared\Transfer\TestDeleteCriteriaTransfer;
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

    public function deleteCollection(TestDeleteCriteriaTransfer $testDeleteCriteriaTransfer): TestResponseTransfer
    {
    }
}

// Database module

namespace Spryker\Zed\Test\Facade;

use Generated\Shared\Transfer\TestDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TestResponseTransfer;

interface SessionTestToTestFacadeInterface
{
    public function deleteCollection(TestDeleteCriteriaTransfer $testDeleteCriteriaTransfer): TestResponseTransfer;
}


namespace Generated\Shared\Transfer;

class TestResponseTransfer
{

}

class TestDeleteCriteriaTransfer
{

}

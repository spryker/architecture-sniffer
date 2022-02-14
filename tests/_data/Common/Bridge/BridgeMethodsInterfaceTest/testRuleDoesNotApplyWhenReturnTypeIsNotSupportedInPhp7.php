<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Bridge\Dependency\Client;

use ArchitectureSnifferTest\Common\Bridge\DataLayer\DataObject;
use ArchitectureSnifferTest\Common\Bridge\DataLayer\ReturnDataObject;

class ValidFooTooBarClientBridge implements ValidFooTooBarClientInterface
{
    /**
     * @param \ArchitectureSnifferTest\Common\Bridge\Yves\Bar\Client\BarClientInterface $barClient
     */
    public function __construct($barClient)
    {
    }

    /**
     * @return mixed
     */
    public function getMixed()
    {
    }

    /**
     * @return false
     */
    public function getFalse()
    {
    }

    /**
     * @return int|float
     */
    public function getIntOrFloat()
    {
    }
}

interface ValidFooTooBarClientInterface
{
    /**
     * @return mixed
     */
    public function getMixed();

    /**
     * @return false
     */
    public function getFalse();

    /**
     * @return int|float
     */
    public function getIntOrFloat();
}

namespace ArchitectureSnifferTest\Common\Bridge\Yves\Bar\Client;

interface BarClientInterface
{
    /**
     * @return mixed
     */
    public function getMixed();

    /**
     * @return false
     */
    public function getFalse();

    /**
     * @return int|float
     */
    public function getIntOrFloat();
}

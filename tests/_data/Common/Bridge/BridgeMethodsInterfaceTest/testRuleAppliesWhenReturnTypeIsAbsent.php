<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Bridge\Dependency\Client;

class NotValidFooTooBarClientBridge implements NotValidFooTooBarClientInterface
{
    /**
     * @param \ArchitectureSnifferTest\Common\Bridge\Zed\Bar\Client\BarClientInterface $barClient
     */
    public function __construct($barClient)
    {
    }
    
    /**
     * @return string
     */
    public function getString()
    {
    }

    /**
     * @return string|null
     */
    public function getStringOrNull()
    {
    }
}

interface NotValidFooTooBarClientInterface
{
    /**
     * @return string
     */
    public function getString();

    /**
     * @return string|null
     */
    public function getStringOrNull();
}

namespace ArchitectureSnifferTest\Common\Bridge\Zed\Bar\Client;

interface BarClientInterface
{
    /**
     * @return string
     */
    public function getString();

    /**
     * @return string|null
     */
    public function getStringOrNull();
}

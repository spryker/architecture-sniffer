<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Bridge\Dependency\Client;

class MustBeStricterFooTooBarClientBridge implements MustBeStricterFooTooBarClientInterface
{
    /**
     * @param \ArchitectureSnifferTest\Common\Bridge\Zed\Bar\Client\BarClientInterface $barClient
     */
    public function __construct($barClient)
    {
    }

    /**
     * @param string|null $key
     *
     * @return string
     */
    public function getByString($key): string
    {
    }

    /**
     * @param mixed $key
     *
     * @return string
     */
    public function getByMixed($key): string
    {
    }

    /**
     * @param int|float $key
     *
     * @return string
     */
    public function getByIntOrFloat($key): string
    {
    }
}

interface MustBeStricterFooTooBarClientInterface
{
    /**
     * @param string|null $key
     *
     * @return string
     */
    public function getByString($key): string;

    /**
     * @param mixed $key
     *
     * @return string
     */
    public function getByMixed($key): string;

    /**
     * @param int|float $key
     *
     * @return string
     */
    public function getByIntOrFloat($key): string;
}

namespace ArchitectureSnifferTest\Common\Bridge\Zed\Bar\Client;

interface MustBeStricterBarClientInterface
{
    /**
     * @param string|null $key
     *
     * @return string
     */
    public function getByString($key): string;

    /**
     * @param mixed $key
     *
     * @return string
     */
    public function getByMixed($key): string;

    /**
     * @param int|float $key
     *
     * @return string
     */
    public function getByIntOrFloat($key): string;
}

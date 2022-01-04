<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Session\Dependency\Client;

use StructuredData\DataObject;

class SessionToCacheClientBridge implements SessionToCacheClientInterface
{
    /**
     * @param \Spryker\Zed\Cache\Client\CacheClientInterface $cacheClient
     */
    public function __construct($cacheClient)
    {
    }

    public function save(string $key, DataObject $data, string $prefix = null)
    {
    }
}

interface SessionToCacheClientInterface
{
    public function save(string $key, DataObject $data, string $prefix = null);
}

// Data objects

namespace StructuredData;

class DataObject
{
}

// Database module

namespace Spryker\Zed\Cache\Client;

use StructuredData\DataObject;

interface CacheClientInterface
{
    public function save(string $key, DataObject $data, $prefix = null, array $options = null);
}

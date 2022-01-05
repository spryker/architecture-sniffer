<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Bridge\Zed\Session\Dependency\Client;

use ArchitectureSnifferTest\Common\Bridge\StructuredData\DataObject;
use ArchitectureSnifferTest\Common\Bridge\StructuredData\ReturnDataObject;

class SessionToCacheClientBridge implements SessionToCacheClientInterface
{
    /**
     * @param \ArchitectureSnifferTest\Common\Bridge\Zed\Cache\Client\CacheClientInterface $cacheClient
     */
    public function __construct($cacheClient)
    {
    }

    public function save(string $key, DataObject $data, string $prefix = null): ReturnDataObject
    {
        return new ReturnDataObject();
    }
}

interface SessionToCacheClientInterface
{
    public function save(string $key, DataObject $data, string $prefix = null): ReturnDataObject;
}

// Data objects

namespace ArchitectureSnifferTest\Common\Bridge\StructuredData;

class DataObject
{
}

class ReturnDataObject
{
}

// Cache module

namespace ArchitectureSnifferTest\Common\Bridge\Zed\Cache\Client;

use ArchitectureSnifferTest\Common\Bridge\StructuredData\DataObject;
use ArchitectureSnifferTest\Common\Bridge\StructuredData\ReturnDataObject;

interface CacheClientInterface
{
    public function save(string $key, DataObject $data, string $prefix = null): ReturnDataObject;
}

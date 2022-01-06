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

    public function exist(string $key): bool
    {
        return true;
    }

    public function find(string $key): ReturnDataObject
    {
        return new ReturnDataObject();
    }

    public function findByData(DataObject $data): ReturnDataObject
    {
        return new ReturnDataObject();
    }

    public function save(string $key, DataObject $data, string $prefix = null): ReturnDataObject
    {
        return new ReturnDataObject();
    }
}

interface SessionToCacheClientInterface
{
    public function find(string $key): ReturnDataObject;

    public function exist(string $key): bool;


    public function findByData(DataObject $data): ReturnDataObject;

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
    public function find($key);

    public function exist($key): bool;

    public function findByData(DataObject $data): ReturnDataObject;

    public function save(string $key, DataObject $data, string $prefix = null): ReturnDataObject;
}

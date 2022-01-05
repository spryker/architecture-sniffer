<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Bridge\Zed\Session\Dependency\Client;

use ArchitectureSnifferTest\Common\Bridge\DataLayer\DataObject;
use ArchitectureSnifferTest\Common\Bridge\DataLayer\ReturnDataObject;

class SessionToDatabaseClientBridge implements SessionToDatabaseClientInterface
{
    /**
     * @param \Spryker\Zed\Database\Client\DatabaseClientInterface $databaseClient
     */
    public function __construct($databaseClient)
    {
    }

    public function find($key)
    {

    }

    public function exist($key)
    {
        return true;
    }

    public function findByData(DataObject  $data)
    {

    }

    public function save(string $key, DataObject $data, string $prefix = null, int $delay = null)
    {
    }
}

interface SessionToDatabaseClientInterface
{
    public function find($key);

    public function exist($key);

    public function findByData(DataObject  $data);


    public function save(string $key, DataObject $data, string $prefix = null);
}

// Database module

namespace ArchitectureSnifferTest\Common\Bridge\Zed\Database\Client;

use ArchitectureSnifferTest\Common\Bridge\DataLayer\DataObject;
use ArchitectureSnifferTest\Common\Bridge\DataLayer\ReturnDataObject;


interface DatabaseClientInterface
{
    public function find(string $key);

    public function exist(string $key): bool;

    public function findByData(DataObject $data): ReturnDataObject;

    public function save(string $key, DataObject $data, string $prefix = null): ReturnDataObject;
}

// Data objects

namespace ArchitectureSnifferTest\Common\Bridge\DataLayer;

class DataObject
{
}

class ReturnDataObject
{
}

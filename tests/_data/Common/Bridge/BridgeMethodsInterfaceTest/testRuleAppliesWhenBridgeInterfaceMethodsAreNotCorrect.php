<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\Bridge\Zed\Session\Dependency\Client;

use DataLayer\DataObject;
use DataLayer\ReturnDataObject;

class SessionToDatabaseClientBridge implements SessionToDatabaseClientInterface
{
    /**
     * @param \Spryker\Zed\Database\Client\DatabaseClientInterface $databaseClient
     */
    public function __construct($databaseClient)
    {
    }

    public function save(string $key, DataObject $data, string $prefix = null, int $delay = null)
    {
    }
}

interface SessionToDatabaseClientInterface
{
    public function save(string $key, DataObject $data, string $prefix = null);
}

// Database module

namespace ArchitectureSnifferTest\Common\Bridge\Zed\Database\Client;

use ArchitectureSnifferTest\Common\Bridge\DataLayer\DataObject;
use ArchitectureSnifferTest\Common\Bridge\DataLayer\ReturnDataObject;


interface DatabaseClientInterface
{
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

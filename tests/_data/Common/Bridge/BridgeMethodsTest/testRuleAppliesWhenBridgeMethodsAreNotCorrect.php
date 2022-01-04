<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Session\Dependency\Client;

use DataLayer\ExtendedDataObject;

class SessionToDatabaseClientBridge implements SessionToDatabaseClientInterface
{
    /**
     * @param \Spryker\Zed\Database\Client\DatabaseClientInterface $databaseClient
     */
    public function __construct($databaseClient)
    {
    }

    public function save(string $key, ExtendedDataObject $data, string $prefix = null, $options = null, int $delay = null )
    {
    }
}

interface SessionToDatabaseClientInterface
{
    public function save(string $key, ExtendedDataObject $data, string $prefix = null, $options = null);
}

// Database module

namespace Spryker\Zed\Database\Client;

use DataLayer\DataObject;

interface DatabaseClientInterface
{
    public function save(string $key, DataObject $data, string $prefix = null, array $options = null);
}

// Data objects

namespace DataLayer;

class DataObject
{
}

class ExtendedDataObject extends DataObject
{
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console\Setup;

use RuntimeException;

class FileUpserter
{
    /**
     * @var string
     */
    public const STATUS_CREATED = 'created';

    /**
     * @var string
     */
    public const STATUS_UPDATED = 'updated';

    /**
     * @var string
     */
    public const STATUS_UNCHANGED = 'unchanged';

    /**
     * @var string
     */
    protected const BACKUP_SUFFIX = '.bak';

    /**
     * @var int
     */
    protected const DIRECTORY_MODE = 0775;

    /**
     * Writes contents to the destination path using an upsert strategy:
     * - missing file => created
     * - existing file with identical contents => unchanged (no write, no backup)
     * - existing file with different contents => previous version kept as <file>.bak, then overwritten
     *
     * @param string $destinationFile
     * @param string $contents
     *
     * @return string One of the STATUS_* constants.
     */
    public function upsert(string $destinationFile, string $contents): string
    {
        $this->ensureDirectory(dirname($destinationFile));

        if (!is_file($destinationFile)) {
            $this->writeFile($destinationFile, $contents);

            return static::STATUS_CREATED;
        }

        $existing = (string)file_get_contents($destinationFile);

        if ($existing === $contents) {
            return static::STATUS_UNCHANGED;
        }

        $this->writeFile($destinationFile . static::BACKUP_SUFFIX, $existing);
        $this->writeFile($destinationFile, $contents);

        return static::STATUS_UPDATED;
    }

    /**
     * @param string $directory
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function ensureDirectory(string $directory): void
    {
        if (is_dir($directory)) {
            return;
        }

        if (!mkdir($directory, static::DIRECTORY_MODE, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Unable to create directory: %s', $directory));
        }
    }

    /**
     * @param string $file
     * @param string $contents
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function writeFile(string $file, string $contents): void
    {
        if (file_put_contents($file, $contents) === false) {
            throw new RuntimeException(sprintf('Unable to write file: %s', $file));
        }
    }
}

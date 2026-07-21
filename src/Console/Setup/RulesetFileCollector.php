<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console\Setup;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class RulesetFileCollector
{
    /**
     * @var string
     */
    protected const XML_EXTENSION = 'xml';

    /**
     * Collects every *.xml ruleset file under the source directory as paths
     * relative to that directory, sorted for deterministic output.
     *
     * @param string $sourceDirectory
     *
     * @return array<int, string>
     */
    public function collect(string $sourceDirectory): array
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourceDirectory, FilesystemIterator::SKIP_DOTS),
        );

        $files = [];
        foreach ($iterator as $fileInfo) {
            /** @var \SplFileInfo $fileInfo */
            if (!$this->isRulesetFile($fileInfo)) {
                continue;
            }

            $files[] = ltrim(str_replace($sourceDirectory, '', $fileInfo->getPathname()), '/');
        }

        sort($files);

        return $files;
    }

    /**
     * @param \SplFileInfo $fileInfo
     *
     * @return bool
     */
    protected function isRulesetFile(SplFileInfo $fileInfo): bool
    {
        return $fileInfo->isFile() && strtolower($fileInfo->getExtension()) === static::XML_EXTENSION;
    }
}

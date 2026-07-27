<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console\Command;

use ArchitectureSniffer\Console\CommandInterface;
use ArchitectureSniffer\Console\CommandResponse;
use ArchitectureSniffer\Console\Formatter\ProjectResultsFormatter;

class FormatProjectResultsCommand implements CommandInterface
{
    protected const string NAME = 'format-project-results';

    public function __construct(protected ProjectResultsFormatter $formatter)
    {
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function getUsage(): string
    {
        return static::NAME . ' <input.json> [<output.txt>]';
    }

    /**
     * @param array<int, string> $arguments
     *
     * @return \ArchitectureSniffer\Console\CommandResponse
     */
    public function run(array $arguments): CommandResponse
    {
        $inputFile = $arguments[0] ?? null;
        $outputFile = $arguments[1] ?? null;

        if ($inputFile === null) {
            return CommandResponse::error([
                'Missing <input.json> argument.',
                'Usage: vendor/bin/spryker-architecture ' . $this->getUsage(),
            ]);
        }

        if (!is_file($inputFile)) {
            return CommandResponse::error([sprintf('Report file not found: %s', $inputFile)]);
        }

        $results = json_decode((string)file_get_contents($inputFile), true);

        if (!is_array($results) || !isset($results['files'])) {
            return CommandResponse::error([sprintf('Invalid or empty phpmd JSON report: %s', $inputFile)]);
        }

        $fileOutput = $this->formatter->format($results);

        if ($outputFile !== null) {
            return $this->writeToFile($outputFile, $fileOutput);
        }

        return CommandResponse::success([$fileOutput]);
    }

    protected function writeToFile(string $outputFile, string $contents): CommandResponse
    {
        if (file_put_contents($outputFile, $contents) === false) {
            return CommandResponse::error([sprintf('Unable to write output file: %s', $outputFile)]);
        }

        return CommandResponse::success([sprintf('Formatted report written to: %s', $outputFile)]);
    }
}

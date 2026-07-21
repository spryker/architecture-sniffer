<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console\Command;

use ArchitectureSniffer\Console\CommandInterface;
use ArchitectureSniffer\Console\Formatter\ProjectResultsFormatter;

class FormatProjectResultsCommand implements CommandInterface
{
    /**
     * @var string
     */
    protected const NAME = 'format-project-results';

    protected ProjectResultsFormatter $formatter;

    /**
     * @param \ArchitectureSniffer\Console\Formatter\ProjectResultsFormatter $formatter
     */
    public function __construct(ProjectResultsFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public function getUsage(): string
    {
        return static::NAME . ' <input.json> [<output.txt>]';
    }

    /**
     * @param array<int, string> $arguments
     *
     * @return int
     */
    public function run(array $arguments): int
    {
        $inputFile = $arguments[0] ?? null;
        $outputFile = $arguments[1] ?? null;

        if ($inputFile === null) {
            fwrite(STDERR, 'Missing <input.json> argument.' . PHP_EOL);
            fwrite(STDERR, 'Usage: vendor/bin/spryker-architecture ' . $this->getUsage() . PHP_EOL);

            return 1;
        }

        if (!is_file($inputFile)) {
            fwrite(STDERR, sprintf('Report file not found: %s%s', $inputFile, PHP_EOL));

            return 1;
        }

        $results = json_decode((string)file_get_contents($inputFile), true);

        if (!is_array($results) || !isset($results['files'])) {
            fwrite(STDERR, sprintf('Invalid or empty phpmd JSON report: %s%s', $inputFile, PHP_EOL));

            return 1;
        }

        $fileOutput = $this->formatter->format($results);

        if ($outputFile !== null) {
            return $this->writeToFile($outputFile, $fileOutput);
        }

        echo $fileOutput;

        return 0;
    }

    /**
     * @param string $outputFile
     * @param string $contents
     *
     * @return int
     */
    protected function writeToFile(string $outputFile, string $contents): int
    {
        if (file_put_contents($outputFile, $contents) === false) {
            fwrite(STDERR, sprintf('Unable to write output file: %s%s', $outputFile, PHP_EOL));

            return 1;
        }

        fwrite(STDOUT, sprintf('Formatted report written to: %s%s', $outputFile, PHP_EOL));

        return 0;
    }
}

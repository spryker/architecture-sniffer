<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console\Command;

use ArchitectureSniffer\Console\CommandInterface;
use ArchitectureSniffer\Console\Setup\FileUpserter;
use ArchitectureSniffer\Console\Setup\RulesetFileCollector;
use ArchitectureSniffer\Console\Setup\RulesetReferenceRewriter;
use RuntimeException;

class SetupProjectCommand implements CommandInterface
{
    /**
     * @var string
     */
    protected const NAME = 'setup-project';

    /**
     * @var string
     */
    protected const DEFAULT_DESTINATION = 'architecture-sniffer';

    /**
     * Package-relative path to the project ruleset source directory.
     *
     * @var string
     */
    protected const PROJECT_SOURCE_RELATIVE_PATH = 'src/Project';

    protected RulesetFileCollector $rulesetFileCollector;

    protected RulesetReferenceRewriter $rulesetReferenceRewriter;

    protected FileUpserter $fileUpserter;

    protected string $packageRootPath;

    /**
     * @param \ArchitectureSniffer\Console\Setup\RulesetFileCollector $rulesetFileCollector
     * @param \ArchitectureSniffer\Console\Setup\RulesetReferenceRewriter $rulesetReferenceRewriter
     * @param \ArchitectureSniffer\Console\Setup\FileUpserter $fileUpserter
     * @param string $packageRootPath Absolute path to the architecture-sniffer package root.
     */
    public function __construct(
        RulesetFileCollector $rulesetFileCollector,
        RulesetReferenceRewriter $rulesetReferenceRewriter,
        FileUpserter $fileUpserter,
        string $packageRootPath
    ) {
        $this->rulesetFileCollector = $rulesetFileCollector;
        $this->rulesetReferenceRewriter = $rulesetReferenceRewriter;
        $this->fileUpserter = $fileUpserter;
        $this->packageRootPath = $packageRootPath;
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
        return static::NAME . ' [<destination>]';
    }

    /**
     * @param array<int, string> $arguments
     *
     * @return int
     */
    public function run(array $arguments): int
    {
        $destination = rtrim($arguments[0] ?? static::DEFAULT_DESTINATION, '/');
        $sourceDirectory = $this->packageRootPath . '/' . static::PROJECT_SOURCE_RELATIVE_PATH;

        if (!is_dir($sourceDirectory)) {
            fwrite(STDERR, sprintf('Project ruleset source directory not found: %s%s', $sourceDirectory, PHP_EOL));

            return 1;
        }

        $rulesetFiles = $this->rulesetFileCollector->collect($sourceDirectory);

        if ($rulesetFiles === []) {
            fwrite(STDERR, sprintf('No ruleset .xml files found under: %s%s', $sourceDirectory, PHP_EOL));

            return 1;
        }

        try {
            return $this->copyRulesets($rulesetFiles, $sourceDirectory, $destination);
        } catch (RuntimeException $exception) {
            fwrite(STDERR, $exception->getMessage() . PHP_EOL);

            return 1;
        }
    }

    /**
     * @param array<int, string> $rulesetFiles
     * @param string $sourceDirectory
     * @param string $destination
     *
     * @return int
     */
    protected function copyRulesets(array $rulesetFiles, string $sourceDirectory, string $destination): int
    {
        $counters = [
            FileUpserter::STATUS_CREATED => 0,
            FileUpserter::STATUS_UPDATED => 0,
            FileUpserter::STATUS_UNCHANGED => 0,
        ];

        foreach ($rulesetFiles as $relativePath) {
            $sourceFile = $sourceDirectory . '/' . $relativePath;
            $destinationFile = $destination . '/' . $relativePath;

            $contents = (string)file_get_contents($sourceFile);
            $contents = $this->rulesetReferenceRewriter->rewrite($contents, $destination);

            $status = $this->fileUpserter->upsert($destinationFile, $contents);
            $counters[$status]++;

            $this->reportFileStatus($status, $destinationFile);
        }

        $this->reportSummary($counters, $destination);

        return 0;
    }

    /**
     * @param string $status
     * @param string $destinationFile
     *
     * @return void
     */
    protected function reportFileStatus(string $status, string $destinationFile): void
    {
        if ($status === FileUpserter::STATUS_CREATED) {
            fwrite(STDOUT, sprintf('  created:   %s%s', $destinationFile, PHP_EOL));

            return;
        }

        if ($status === FileUpserter::STATUS_UPDATED) {
            fwrite(STDOUT, sprintf('  updated:   %s (backup: %s.bak)%s', $destinationFile, $destinationFile, PHP_EOL));
        }
    }

    /**
     * @param array<string, int> $counters
     * @param string $destination
     *
     * @return void
     */
    protected function reportSummary(array $counters, string $destination): void
    {
        fwrite(STDOUT, PHP_EOL);
        fwrite(STDOUT, sprintf(
            'Setup complete in "%s": %d created, %d updated, %d unchanged.%s',
            $destination,
            $counters[FileUpserter::STATUS_CREATED],
            $counters[FileUpserter::STATUS_UPDATED],
            $counters[FileUpserter::STATUS_UNCHANGED],
            PHP_EOL,
        ));
        fwrite(STDOUT, sprintf('Run phpmd against: %s/ruleset.xml%s', $destination, PHP_EOL));
    }
}

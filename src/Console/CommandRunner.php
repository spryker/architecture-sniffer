<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console;

class CommandRunner
{
    /**
     * @var array<string, \ArchitectureSniffer\Console\CommandInterface>
     */
    protected array $commands = [];

    /**
     * @param array<int, \ArchitectureSniffer\Console\CommandInterface> $commands
     */
    public function __construct(array $commands)
    {
        foreach ($commands as $command) {
            $this->commands[$command->getName()] = $command;
        }
    }

    /**
     * @param array<int, string> $argv Raw process argv (including the script name at index 0).
     *
     * @return int Process exit code.
     */
    public function run(array $argv): int
    {
        $commandName = $argv[1] ?? null;

        if ($commandName === null) {
            fwrite(STDERR, 'No command given.' . PHP_EOL);
            $this->printUsage();

            return 1;
        }

        if (!isset($this->commands[$commandName])) {
            fwrite(STDERR, sprintf('Unknown command: %s%s', $commandName, PHP_EOL));
            $this->printUsage();

            return 1;
        }

        return $this->commands[$commandName]->run(array_slice($argv, 2));
    }

    /**
     * @return void
     */
    protected function printUsage(): void
    {
        fwrite(STDERR, PHP_EOL);
        fwrite(STDERR, 'Usage:' . PHP_EOL);
        fwrite(STDERR, '  vendor/bin/spryker-architecture <command> [arguments]' . PHP_EOL);
        fwrite(STDERR, PHP_EOL);
        fwrite(STDERR, 'Commands:' . PHP_EOL);

        foreach ($this->commands as $command) {
            fwrite(STDERR, '  ' . $command->getUsage() . PHP_EOL);
        }
    }
}

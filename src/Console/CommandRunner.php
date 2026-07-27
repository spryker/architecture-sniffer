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
     * @param array<int, string> $argv
     */
    public function run(array $argv): int
    {
        $response = $this->resolveResponse($argv);

        return $this->render($response);
    }

    /**
     * @param array<int, string> $argv
     */
    protected function resolveResponse(array $argv): CommandResponse
    {
        $commandName = $argv[1] ?? null;

        if ($commandName === null) {
            return CommandResponse::error(array_merge(['No command given.'], $this->getUsageLines()));
        }

        if (!isset($this->commands[$commandName])) {
            return CommandResponse::error(array_merge(
                [sprintf('Unknown command: %s', $commandName)],
                $this->getUsageLines(),
            ));
        }

        return $this->commands[$commandName]->run(array_slice($argv, 2));
    }

    protected function render(CommandResponse $response): int
    {
        foreach ($response->getOutputMessages() as $outputMessage) {
            $this->writeln(STDOUT, $outputMessage);
        }

        foreach ($response->getErrorMessages() as $errorMessage) {
            $this->writeln(STDERR, $errorMessage);
        }

        return $response->getExitCode();
    }

    /**
     * @param resource $stream
     */
    protected function writeln($stream, string $message): void
    {
        $this->write($stream, $message . PHP_EOL);
    }

    /**
     * @param resource $stream
     */
    protected function write($stream, string $message): void
    {
        fwrite($stream, $message);
    }

    /**
     * @return array<int, string>
     */
    protected function getUsageLines(): array
    {
        $usageLines = [
            '',
            'Usage:',
            '  vendor/bin/spryker-architecture <command> [arguments]',
            '',
            'Commands:',
        ];

        foreach ($this->commands as $command) {
            $usageLines[] = '  ' . $command->getUsage();
        }

        return $usageLines;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console;

/**
 * Immutable result of a command execution. A command builds and returns this object
 * without performing any I/O itself; the CommandRunner renders the collected messages
 * to STDOUT/STDERR and uses the exit code as the process exit code.
 */
class CommandResponse
{
    protected const int EXIT_CODE_SUCCESS = 0;

    protected const int EXIT_CODE_ERROR = 1;

    /**
     * @param int $exitCode
     * @param array<int, string> $outputMessages Messages to render to STDOUT.
     * @param array<int, string> $errorMessages Messages to render to STDERR.
     */
    public function __construct(
        protected int $exitCode = self::EXIT_CODE_SUCCESS,
        protected array $outputMessages = [],
        protected array $errorMessages = []
    ) {
    }

    /**
     * @param array<int, string> $outputMessages Messages to render to STDOUT.
     *
     * @return self
     */
    public static function success(array $outputMessages = []): self
    {
        return new self(static::EXIT_CODE_SUCCESS, $outputMessages);
    }

    /**
     * @param array<int, string> $errorMessages Messages to render to STDERR.
     *
     * @return self
     */
    public static function error(array $errorMessages = []): self
    {
        return new self(static::EXIT_CODE_ERROR, [], $errorMessages);
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function isSuccessful(): bool
    {
        return $this->exitCode === static::EXIT_CODE_SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    public function getOutputMessages(): array
    {
        return $this->outputMessages;
    }

    /**
     * @return array<int, string>
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }
}

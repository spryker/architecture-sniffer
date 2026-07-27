<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console;

interface CommandInterface
{
    /**
     * The command name as typed on the CLI, e.g. "format-project-results".
     */
    public function getName(): string;

    /**
     * One-line usage summary shown in the global usage output.
     */
    public function getUsage(): string;

    /**
     * Executes the command and returns its result without performing any I/O.
     *
     * @param array<int, string> $arguments Arguments following the command name.
     *
     * @return \ArchitectureSniffer\Console\CommandResponse
     */
    public function run(array $arguments): CommandResponse;
}

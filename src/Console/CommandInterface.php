<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console;

interface CommandInterface
{
    /**
     * The command name as typed on the CLI, e.g. "setup-project".
     *
     * @return string
     */
    public function getName(): string;

    /**
     * One-line usage summary shown in the global usage output.
     *
     * @return string
     */
    public function getUsage(): string;

    /**
     * Executes the command.
     *
     * @param array<int, string> $arguments Arguments following the command name.
     *
     * @return int Process exit code.
     */
    public function run(array $arguments): int;
}

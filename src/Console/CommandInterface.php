<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console;

interface CommandInterface
{
    public function getName(): string;

    public function getUsage(): string;

    /**
     * @param array<int, string> $arguments
     */
    public function run(array $arguments): CommandResponse;
}

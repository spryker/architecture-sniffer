<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

trait PhpTypesTrait
{
    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isTypeInPhp7NotAllowed(string $type): bool
    {
        $type = str_replace('|null', '', $type);

        return strpos($type, '|') !== false
            || $type === 'mixed'
            || $type === 'false';
    }
}

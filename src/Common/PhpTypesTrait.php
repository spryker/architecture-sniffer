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
     * @return string
     */
    protected function stripNullReturnTypeHint(string $type): string
    {
        return str_replace('|null', '', $type);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isTypeInPhp7NotAllowed(string $type): bool
    {
        $type = $this->stripNullReturnTypeHint($type);

        return strpos($type, '|') !== false
            || $type === 'mixed'
            || $type === 'false'
            || $type === 'static';
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isReturnTypeIsThis(string $type): bool
    {
        $type = $type = $this->stripNullReturnTypeHint($type);

        return strpos($type, '|') !== false
            || $type === '$this';
    }
}

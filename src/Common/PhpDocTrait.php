<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

trait PhpDocTrait
{
    /**
     * @param string|null $phpDoc
     *
     * @return string|null
     */
    protected function getReturnTypeByPhpDoc(?string $phpDoc): ?string
    {
        if ($phpDoc === null) {
            return null;
        }

        $matches = [];

        preg_match_all('/@return\s+([^\s]+)/', $phpDoc, $matches);

        return $matches[1][0] ?? null;
    }

    /**
     * @param string|null $phpDoc
     *
     * @return bool
     */
    protected function inheritDocTagExists(?string $phpDoc): bool
    {
        if ($phpDoc === null) {
            return false;
        }

        return stripos($phpDoc, '@inheritDoc') !== false;
    }

    /**
     * @param string|null $phpDoc
     *
     * @return bool
     */
    protected function apiTagExists(?string $phpDoc): bool
    {
        if ($phpDoc === null) {
            return false;
        }

        return stripos($phpDoc, '@api') !== false;
    }

    /**
     * @param string|null $phpDoc
     * @param string $paramName
     *
     * @return string|null
     */
    protected function getParamTypeByPhpDoc(?string $phpDoc, string $paramName): ?string
    {
        if ($phpDoc === null) {
            return null;
        }

        $matches = [];

        preg_match_all(sprintf('/@param ([^\s]+) \$%s/', $paramName), $phpDoc, $matches);

        return $matches[1][0] ?? null;
    }
}

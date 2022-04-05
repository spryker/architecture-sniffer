<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\DeprecatedObjectUsageJsonDecodeRuleTest;

class Bar
{
    protected $utilEncodingService;

    /**
     * @return bool
     */
    public function test(): bool
    {
        return $this->utilEncodingService->decodeJson($value, true);
    }
}

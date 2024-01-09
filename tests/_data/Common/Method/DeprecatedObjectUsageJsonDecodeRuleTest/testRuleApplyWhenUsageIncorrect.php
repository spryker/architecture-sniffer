<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSnifferTest\Common\DeprecatedObjectUsageJsonDecodeRuleTest;

class Foo
{
    protected $utilEncodingService;

    /**
     * @return bool
     */
    public function testOne(): bool
    {
        return $this->utilEncodingService->decodeJson($value);
    }

    /**
     * @return bool
     */
    public function testTwo(): bool
    {
        return $this->utilEncodingService->decodeJson($value, false);
    }
}

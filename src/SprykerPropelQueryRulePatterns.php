<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer;

interface SprykerPropelQueryRulePatterns
{
    public const PATTERN_PROPEL_QUERY_CONSTANT_NAME = '/^PROPEL_QUERY_.+/';
    public const PATTERN_PROPEL_QUERY_DEPENDENCY_PROVIDER_METHOD_NAME = '/^add([a-zA-Z]+)PropelQuery$/';
    public const PATTERN_PROPEL_QUERY_FACTORY_METHOD_NAME = '/^get([a-zA-Z]+)PropelQuery$/';
}

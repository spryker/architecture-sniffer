<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console\Setup;

class RulesetReferenceRewriter
{
    /**
     * Package path prefix used inside ruleset <rule ref="..."> attributes that must
     * be rewritten to the destination when copied to the project level.
     *
     * @var string
     */
    protected const PACKAGE_REF_PREFIX = 'vendor/spryker/architecture-sniffer/src/Project/';

    /**
     * Rewrites package-relative project ruleset references to the destination folder.
     * phpmd refs (vendor/phpmd/...) and class="ArchitectureSniffer\..." attributes are
     * left untouched, so their rules keep resolving through the package autoloader.
     *
     * @param string $contents
     * @param string $destination
     *
     * @return string
     */
    public function rewrite(string $contents, string $destination): string
    {
        return str_replace(static::PACKAGE_REF_PREFIX, $destination . '/', $contents);
    }
}

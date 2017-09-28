<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

use PHPMD\Node\MethodNode;

trait DeprecationTrait
{

    /**
     * @var string
     */
    protected $regexp = '(@([a-z_][a-z0-9_]+))i';

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function isMethodDeprecated(MethodNode $method)
    {
        preg_match_all($this->regexp, $method->getNode()->getDocComment(), $matches);
        foreach (array_keys($matches[0]) as $i) {
            if ($matches[1][$i] === 'deprecated') {
                return true;
            }
        }

        return false;
    }
}

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
    protected $regexp = '/@deprecated/i';

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function isMethodDeprecated(MethodNode $method)
    {
        return preg_match($this->regexp, $method->getNode()->getDocComment());
    }

}

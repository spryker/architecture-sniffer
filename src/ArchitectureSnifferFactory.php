<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;

class ArchitectureSnifferFactory
{
    /**
     * @param string $methodDocComment
     *
     * @return \phpDocumentor\Reflection\DocBlock
     */
    public function createPhpDocumetorDocBlock(string $methodDocComment): DocBlock
    {
        return DocBlockFactory::createInstance()->create($methodDocComment);
    }
}

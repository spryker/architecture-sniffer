<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Node\DocBlock\Reader;

use PHPMD\AbstractNode;

interface DocBlockNodeReaderInterface
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return array
     */
    public function getModuleNames(AbstractNode $node): array;
}

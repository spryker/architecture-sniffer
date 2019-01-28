<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Node\Reader;

use PHPMD\AbstractNode;
use Roave\BetterReflection\Reflection\ReflectionClass;

interface NodeReaderInterface
{
    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getModuleName(AbstractNode $node): string;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getClassName(AbstractNode $node): string;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \Roave\BetterReflection\Reflection\ReflectionClass
     */
    public function getReflectionClassByNode(AbstractNode $node): ReflectionClass;
}

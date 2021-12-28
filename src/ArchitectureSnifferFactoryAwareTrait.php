<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer;

trait ArchitectureSnifferFactoryAwareTrait
{
    /**
     * @var \ArchitectureSniffer\ArchitectureSnifferFactory
     */
    protected $factory;

    /**
     * @return \ArchitectureSniffer\ArchitectureSnifferFactory
     */
    protected function getFactory(): ArchitectureSnifferFactory
    {
        if ($this->factory === null) {
            $this->factory = new ArchitectureSnifferFactory();
        }

        return $this->factory;
    }
}

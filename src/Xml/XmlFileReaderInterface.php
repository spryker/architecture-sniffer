<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Xml;

interface XmlFileReaderInterface
{
    /**
     * @param string $xmlFilePath
     *
     * @throws \ArchitectureSniffer\Xml\Exception\XmlReadException
     *
     * @return array<mixed>
     */
    public function readFile(string $xmlFilePath): array;
}

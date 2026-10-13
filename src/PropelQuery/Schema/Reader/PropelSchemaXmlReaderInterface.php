<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery\Schema\Reader;

interface PropelSchemaXmlReaderInterface
{
    /**
     * @param string $filePath
     *
     * @throws \RuntimeException
     *
     * @return array<mixed>
     */
    public function fromFile(string $filePath): array;
}

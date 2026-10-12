<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery\Schema\Reader;

use RuntimeException;
use XMLReader;

class PropelSchemaXmlReader implements PropelSchemaXmlReaderInterface
{
    protected const string TEXT_KEY = '_';

    /**
     * @var list<int>
     */
    protected const array TEXT_NODE_TYPES = [
        XMLReader::TEXT,
        XMLReader::CDATA,
        XMLReader::WHITESPACE,
        XMLReader::SIGNIFICANT_WHITESPACE,
    ];

    /**
     * @param string $filePath
     *
     * @throws \RuntimeException
     *
     * @return array<mixed>
     */
    public function fromFile(string $filePath): array
    {
        $useInternalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();

        try {
            $content = $this->readFile($filePath);
            $error = libxml_get_last_error();

            if ($error !== false) {
                throw new RuntimeException(sprintf('XML file "%s" is not well formed: %s', $filePath, trim($error->message)));
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($useInternalErrors);
        }

        return is_array($content) ? $content : [];
    }

    /**
     * @param string $filePath
     *
     * @throws \RuntimeException
     *
     * @return array<mixed>|string
     */
    protected function readFile(string $filePath): array|string
    {
        $reader = new XMLReader();

        if (!is_file($filePath) || $reader->open($filePath, null, LIBXML_XINCLUDE) === false) {
            throw new RuntimeException(sprintf('Cannot open XML file "%s".', $filePath));
        }

        try {
            return $this->readNextElement($reader);
        } finally {
            $reader->close();
        }
    }

    /**
     * @param \XMLReader $reader
     *
     * @return array<mixed>|string
     */
    protected function readNextElement(XMLReader $reader): array|string
    {
        $children = [];
        $text = '';

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT) {
                if ($reader->depth === 0) {
                    return $this->readNextElement($reader);
                }

                $children = $this->addChild($reader, $children);

                continue;
            }

            if ($reader->nodeType === XMLReader::END_ELEMENT) {
                break;
            }

            if (in_array($reader->nodeType, static::TEXT_NODE_TYPES, true)) {
                $text .= $reader->value;
            }
        }

        return $children ?: $text;
    }

    /**
     * @param \XMLReader $reader
     * @param array<mixed> $children
     *
     * @return array<mixed>
     */
    protected function addChild(XMLReader $reader, array $children): array
    {
        $attributes = $this->readAttributes($reader);
        $name = $reader->name;
        $child = $reader->isEmptyElement ? [] : $this->readNextElement($reader);

        if ($attributes !== []) {
            if (is_string($child)) {
                $child = [static::TEXT_KEY => $child];
            }

            $child = array_merge($child, $attributes);
        }

        if (!isset($children[$name])) {
            $children[$name] = $child;

            return $children;
        }

        if (!is_array($children[$name]) || !array_key_exists(0, $children[$name])) {
            $children[$name] = [$children[$name]];
        }

        $children[$name][] = $child;

        return $children;
    }

    /**
     * @param \XMLReader $reader
     *
     * @return array<string, string>
     */
    protected function readAttributes(XMLReader $reader): array
    {
        if (!$reader->hasAttributes) {
            return [];
        }

        $attributes = [];
        $reader->moveToFirstAttribute();

        do {
            $attributes[$reader->localName] = $reader->value;
        } while ($reader->moveToNextAttribute());

        $reader->moveToElement();

        return $attributes;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Xml;

use ArchitectureSniffer\Xml\Exception\XmlReadException;
use DOMDocument;
use LibXMLError;
use XMLReader;

/**
 * Reads an XML file into the nested array shape that `Laminas\Config\Reader\Xml` produced, so that
 * callers keep the exact structure they were written against after `laminas/laminas-config` (which
 * is abandoned) was dropped.
 *
 * The shape rules this reproduces, all of them relied on by the Propel schema parsing:
 *
 * - attributes become plain string keys alongside the element's children;
 * - an element carrying attributes AND text stores the text under `_`;
 * - a repeated element name becomes a numerically indexed list, a single one stays associative
 *   (`PropelSchemaTableFinder` branches on `isset($tables['name'])` to tell the two apart);
 * - values are NEVER coerced - `primaryKey="false"` stays the string `'false'`;
 * - `<foo/>` yields `[]` while `<foo></foo>` yields `''`.
 *
 * That last rule is why the walk uses XMLReader rather than a DOM tree: DOM cannot distinguish a
 * self-closing element from an empty pair. The document is loaded through DOMDocument first only
 * to turn libxml's warnings into a typed exception - XMLReader alone walks a broken file and
 * silently returns less.
 *
 * Kept dependency-free on purpose: this package is a standalone PHPMD plugin and must not pull in
 * the Spryker framework (`spryker/util-encoding` ships the same reader for application code).
 */
class XmlFileReader implements XmlFileReaderInterface
{
    /**
     * @var string
     */
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
     * @param string $xmlFilePath
     *
     * @throws \ArchitectureSniffer\Xml\Exception\XmlReadException
     *
     * @return array<mixed>
     */
    public function readFile(string $xmlFilePath): array
    {
        $this->assertParsable($xmlFilePath);

        $reader = new XMLReader();
        if ($reader->open($xmlFilePath, null, LIBXML_XINCLUDE) === false) {
            throw new XmlReadException(sprintf('Cannot open XML file "%s".', $xmlFilePath));
        }

        try {
            $content = $this->readNextElement($reader);
        } finally {
            $reader->close();
        }

        return is_array($content) ? $content : [];
    }

    /**
     * @param string $xmlFilePath
     *
     * @throws \ArchitectureSniffer\Xml\Exception\XmlReadException
     *
     * @return void
     */
    protected function assertParsable(string $xmlFilePath): void
    {
        $content = is_file($xmlFilePath) ? file_get_contents($xmlFilePath) : false;

        if ($content === false) {
            throw new XmlReadException(sprintf('XML file "%s" does not exist or is not readable.', $xmlFilePath));
        }

        if (trim($content) === '') {
            throw new XmlReadException(sprintf('XML file "%s" does not contain valid XML, it is empty.', $xmlFilePath));
        }

        $internalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $document = new DOMDocument();
        $document->validateOnParse = true;
        $isLoaded = $document->loadXML($content, LIBXML_NONET | LIBXML_COMPACT);

        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        if ($isLoaded === false) {
            throw new XmlReadException(
                sprintf('XML file "%s" is not well formed: %s', $xmlFilePath, $this->formatErrors($errors)),
            );
        }
    }

    /**
     * @param array<\LibXMLError> $errors
     *
     * @return string
     */
    protected function formatErrors(array $errors): string
    {
        $messages = array_map(
            static fn (LibXMLError $error): string => sprintf(
                '[line %d, column %d] %s',
                $error->line,
                $error->column,
                trim($error->message),
            ),
            $errors,
        );

        return $messages === [] ? 'unknown libxml error.' : implode(' ', $messages);
    }

    /**
     * @param \XMLReader $reader
     *
     * @return array<mixed>|string
     */
    protected function readNextElement(XMLReader $reader)
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

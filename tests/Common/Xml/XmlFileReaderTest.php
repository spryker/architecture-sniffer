<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Xml;

use ArchitectureSniffer\Xml\Exception\XmlReadException;
use ArchitectureSniffer\Xml\XmlFileReader;
use Codeception\Test\Unit;

/**
 * Locks the array shape this reader inherited from `Laminas\Config\Reader\Xml`. Every assertion
 * here is a structure `PropelSchemaTableFinder` already branches on, so a "cleaner" shape is a
 * breaking change, not an improvement.
 */
class XmlFileReaderTest extends Unit
{
    protected const string FIXTURE_PATH = __DIR__ . '/../../_data/XmlFileReaderFixtures';

    public function testGivenASingleTableSchemaWhenReadFileIsCalledThenTheTableStaysAssociative(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Act
        $schema = $xmlFileReader->readFile($this->getFixturePath('single_table.schema.xml'));

        // Assert
        $this->assertArrayNotHasKey(0, $schema['table']);
        $this->assertSame('spy_comment', $schema['table']['name']);
        $this->assertSame('SpyComment', $schema['table']['phpName']);
    }

    public function testGivenARepeatedTableElementWhenReadFileIsCalledThenTheTablesBecomeAList(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Act
        $schema = $xmlFileReader->readFile($this->getFixturePath('repeated_tables.schema.xml'));

        // Assert
        $this->assertArrayHasKey(0, $schema['table']);
        $this->assertCount(2, $schema['table']);
        $this->assertSame('spy_comment', $schema['table'][0]['name']);
        $this->assertSame('spy_comment_tag', $schema['table'][1]['name']);
    }

    public function testGivenASingleForeignKeyWhenReadFileIsCalledThenItIsReadableWithoutAnIndex(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Act
        $schema = $xmlFileReader->readFile($this->getFixturePath('single_table.schema.xml'));

        // Assert
        $this->assertSame('spy_customer', $schema['table']['foreign-key']['foreignTable']);
        $this->assertSame('SpyCustomer', $schema['table']['foreign-key']['phpName']);
        $this->assertSame('SpyComment', $schema['table']['foreign-key']['refPhpName']);
    }

    public function testGivenRepeatedForeignKeysWhenReadFileIsCalledThenTheyBecomeAList(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Act
        $schema = $xmlFileReader->readFile($this->getFixturePath('repeated_tables.schema.xml'));

        // Assert
        $this->assertCount(2, $schema['table'][0]['foreign-key']);
        $this->assertSame('spy_customer', $schema['table'][0]['foreign-key'][0]['foreignTable']);
        $this->assertSame('spy_comment_tag', $schema['table'][0]['foreign-key'][1]['foreignTable']);
    }

    /**
     * `PropelSchemaTableFinder::isOwnerOfTable()` reads `primaryKey` through `array_column()`,
     * which needs the columns of a multi-column table as a list of attribute maps.
     *
     * @return void
     */
    public function testGivenATableWithColumnsWhenReadFileIsCalledThenAttributesBecomePlainKeys(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Act
        $schema = $xmlFileReader->readFile($this->getFixturePath('single_table.schema.xml'));

        // Assert
        $this->assertSame(
            [
                ['name' => 'id_comment', 'type' => 'INTEGER', 'autoIncrement' => 'true', 'primaryKey' => 'true'],
                ['name' => 'message', 'type' => 'LONGVARCHAR', 'required' => 'true'],
            ],
            $schema['table']['column'],
        );
    }

    public function testGivenScalarLookingValuesWhenReadFileIsCalledThenTheyAreNeverCoerced(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Act
        $shapes = $xmlFileReader->readFile($this->getFixturePath('shapes.xml'));

        // Assert
        $this->assertSame('0', $shapes['zero']);
        $this->assertSame('007', $shapes['padded']);
        $this->assertSame('false', $shapes['boolish']);
    }

    public function testGivenAnElementWithAttributesAndTextWhenReadFileIsCalledThenTheTextIsStoredUnderTheUnderscoreKey(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Act
        $shapes = $xmlFileReader->readFile($this->getFixturePath('shapes.xml'));

        // Assert
        $this->assertSame(['_' => '  text body  ', 'rule' => 'R'], $shapes['attributed']);
        $this->assertSame(['_' => 'cdata body', 'rule' => 'C'], $shapes['cdata']);
    }

    public function testGivenASelfClosingElementWhenReadFileIsCalledThenItReadsAsAnEmptyArray(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Act
        $shapes = $xmlFileReader->readFile($this->getFixturePath('shapes.xml'));

        // Assert
        $this->assertSame([], $shapes['selfClosing']);
        $this->assertIsString($shapes['emptyWithWhitespace']);
        $this->assertSame('', trim($shapes['emptyWithWhitespace']));
    }

    public function testGivenAnAttributeAndAChildOfTheSameNameWhenReadFileIsCalledThenTheAttributeWins(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Act
        $shapes = $xmlFileReader->readFile($this->getFixturePath('shapes.xml'));

        // Assert
        $this->assertSame('fromAttribute', $shapes['collision']['name']);
    }

    public function testGivenAMalformedFileWhenReadFileIsCalledThenAnExceptionIsThrown(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Assert
        $this->expectException(XmlReadException::class);

        // Act
        $xmlFileReader->readFile($this->getFixturePath('malformed.schema.xml'));
    }

    public function testGivenAnEmptyFileWhenReadFileIsCalledThenAnExceptionIsThrown(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Assert
        $this->expectException(XmlReadException::class);

        // Act
        $xmlFileReader->readFile($this->getFixturePath('empty.schema.xml'));
    }

    public function testGivenAMissingFileWhenReadFileIsCalledThenAnExceptionIsThrown(): void
    {
        // Arrange
        $xmlFileReader = new XmlFileReader();

        // Assert
        $this->expectException(XmlReadException::class);

        // Act
        $xmlFileReader->readFile($this->getFixturePath('does-not-exist.schema.xml'));
    }

    /**
     * @param string $fixtureName
     *
     * @return string
     */
    protected function getFixturePath(string $fixtureName): string
    {
        return static::FIXTURE_PATH . DIRECTORY_SEPARATOR . $fixtureName;
    }
}

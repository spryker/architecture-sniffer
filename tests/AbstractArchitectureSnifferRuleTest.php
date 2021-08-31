<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest;

use Codeception\Test\Unit;
use ErrorException;
use Iterator;
use PDepend\Source\AST\ASTArtifact;
use PDepend\Source\Language\PHP\PHPBuilder;
use PDepend\Source\Language\PHP\PHPParserGeneric;
use PDepend\Source\Language\PHP\PHPTokenizerInternal;
use PDepend\Util\Cache\Driver\MemoryCacheDriver;
use PHPMD\Node\ClassNode;
use PHPMD\Node\FunctionNode;
use PHPMD\Node\InterfaceNode;
use PHPMD\Node\MethodNode;
use PHPMD\Node\TraitNode;
use PHPMD\Report;

abstract class AbstractArchitectureSnifferRuleTest extends Unit
{
    /**
     * @var string|null
     */
    protected $testFile;

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->testFile = null;
    }

    /**
     * @param string $testFile
     *
     * @return void
     */
    protected function setTestFile(string $testFile): void
    {
        $this->testFile = $testFile;
    }

    /**
     * @param string|int|null $nameOrPosition
     *
     * @return \PHPMD\Node\ClassNode
     */
    protected function getClassNode($nameOrPosition = null): ClassNode
    {
        return new ClassNode(
            $this->getNodeForCallingTestCase(
                $this->parseTestCaseSource()->getClasses(),
                $nameOrPosition
            )
        );
    }

    /**
     * @param string|int|null $nameOrPosition
     *
     * @return \PHPMD\Node\InterfaceNode
     */
    protected function getInterfaceNode($nameOrPosition = null)
    {
        return new InterfaceNode(
            $this->getNodeForCallingTestCase(
                $this->parseTestCaseSource()->getInterfaces(),
                $nameOrPosition
            )
        );
    }

    /**
     * @param string|int|null $nameOrPosition
     *
     * @return \PHPMD\Node\TraitNode
     */
    protected function getTraitNode($nameOrPosition = null): TraitNode
    {
        return new TraitNode(
            $this->getNodeForCallingTestCase(
                $this->parseTestCaseSource()->getTraits(),
                $nameOrPosition
            )
        );
    }

    /**
     * @param string|int|null $nameOrPosition
     *
     * @return \PHPMD\Node\MethodNode
     */
    protected function getMethodNode($nameOrPosition = null): MethodNode
    {
        return new MethodNode(
            $this->getNodeForCallingTestCase(
                $this->parseTestCaseSource()
                    ->getTypes()
                    ->current()
                    ->getMethods(),
                $nameOrPosition
            )
        );
    }

    /**
     * @param string|int|null $nameOrPosition
     *
     * @return \PHPMD\Node\FunctionNode
     */
    protected function getFunction($nameOrPosition = null): FunctionNode
    {
        return new FunctionNode(
            $this->getNodeForCallingTestCase(
                $this->parseTestCaseSource()->getFunctions(),
                $nameOrPosition
            )
        );
    }

    /**
     * @return string
     */
    protected function getAbsolutePathToTestFile(): string
    {
        return codecept_data_dir() . $this->getRelativePathToTestFile() . $this->getFileName();
    }

    /**
     * @return string
     */
    protected function getRelativePathToTestFile(): string
    {
        $classNameFragments = explode('\\', static::class);
        array_shift($classNameFragments);

        return implode(DIRECTORY_SEPARATOR, $classNameFragments) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function getFileName(): string
    {
        if ($this->testFile !== null) {
            return $this->testFile;
        }

        return $this->getName() . '.php';
    }

    /**
     * @return \PDepend\Source\AST\ASTArtifact
     */
    private function parseTestCaseSource(): ASTArtifact
    {
        return $this->parseSourceFile($this->getAbsolutePathToTestFile());
    }

    /**
     * @param \Iterator|\PDepend\Source\AST\ASTArtifact[] $nodes
     * @param string|int|null $nameOrPosition
     *
     * @throws \ErrorException
     *
     * @return \PDepend\Source\AST\ASTArtifact
     */
    protected function getNodeForCallingTestCase(Iterator $nodes, $nameOrPosition = null): ASTArtifact
    {
        if ($nameOrPosition === null) {
            return $nodes->current();
        }

        if (is_int($nameOrPosition)) {
            return $nodes[$nameOrPosition];
        }

        foreach ($nodes as $node) {
            if ($node->getName() === $nameOrPosition) {
                return $node;
            }
        }

        throw new ErrorException(sprintf('Could not find node'));
    }

    /**
     * @param string $sourceFile
     *
     * @throws \ErrorException
     *
     * @return \PDepend\Source\AST\ASTArtifact
     */
    protected function parseSourceFile(string $sourceFile): ASTArtifact
    {
        if (!file_exists($sourceFile)) {
            throw new ErrorException(sprintf('Could not find file "%s" for testing. Did you renamed your test method? Expected file name "%s"', $this->getAbsolutePathToTestFile(), $this->getFileName()));
        }

        $tokenizer = new PHPTokenizerInternal();
        $tokenizer->setSourceFile($sourceFile);

        $builder = new PHPBuilder();

        $parser = new PHPParserGeneric(
            $tokenizer,
            $builder,
            new MemoryCacheDriver()
        );
        $parser->parse();

        return $builder->getNamespaces()->current();
    }

    /**
     * @param int $expectedInvokes
     *
     * @return \PHPMD\Report|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getReportMock(int $expectedInvokes = -1): Report
    {
        if ($expectedInvokes < 0) {
            $expects = $this->atLeastOnce();
        } elseif ($expectedInvokes === 0) {
            $expects = $this->never();
        } elseif ($expectedInvokes === 1) {
            $expects = $this->once();
        } else {
            $expects = $this->exactly($expectedInvokes);
        }

        $report = $this->getMockBuilder(Report::class)
            ->setMethods(['addRuleViolation'])
            ->getMock();

        $report->expects($expects)
            ->method('addRuleViolation');

        return $report;
    }
}

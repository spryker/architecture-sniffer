<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer;

use PHPMD\PHPMD;
use ArchitectureSniffer\TextRenderer;
use PHPMD\RuleSetFactory;
use PHPMD\Writer\StreamWriter;

class AbstractArchitectureSnifferTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @param \ArchitectureSniffer\AbstractArchitectureSnifferTestCase $testCase
     *
     * @return string
     */
    private function getFixtureDirectory(AbstractArchitectureSnifferTestCase $testCase)
    {
        $className = substr(get_class($testCase), 20, -4);

        return __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className);
    }

    /**
     * @param \ArchitectureSniffer\AbstractArchitectureSnifferTestCase $testCase
     *
     * @return void
     */
    protected function expectValid(AbstractArchitectureSnifferTestCase $testCase)
    {
        $this->getRuleName($testCase);
        $testDirectory = $this->getFixtureDirectory($testCase) . DIRECTORY_SEPARATOR . 'Valid';
        $ruleSetPath = $this->getRuleSetPath();

        $ruleSetFactory = new RuleSetFactory();
        $phpMd = new PHPMD();
        $renderer = $this->getRenderer();

        $phpMd->processFiles($testDirectory, $ruleSetPath, [$renderer], $ruleSetFactory);

        $this->assertFalse($phpMd->hasViolations());
    }

    /**
     * @param \ArchitectureSniffer\AbstractArchitectureSnifferTestCase $testCase
     *
     * @return void
     */
    protected function expectInValid(AbstractArchitectureSnifferTestCase $testCase)
    {
        $testDirectory = $this->getFixtureDirectory($testCase) . DIRECTORY_SEPARATOR . 'InValid';
        $fileSystemIterator = new \FilesystemIterator($testDirectory, \FilesystemIterator::SKIP_DOTS);
        $expectedViolationCount = iterator_count($fileSystemIterator);

        $ruleSetPath = $this->getRuleSetPath();

        $ruleSetFactory = new RuleSetFactory();
        $phpMd = new PHPMD();
        $renderer = $this->getRenderer();
        $renderer->setCurrentRuleName($this->getRuleName($testCase));

        $phpMd->processFiles($testDirectory, $ruleSetPath, [$renderer], $ruleSetFactory);

        $this->assertTrue($phpMd->hasViolations());

        $violatingRuleNames = $renderer->getOtherViolatingRuleNames();

        $this->assertCount(
            0,
            $violatingRuleNames,
            sprintf(
                'Following rule%s also matched "%s". This means that your fixture file contains a violation which is not expected. See output above to fix this issue.',
                count($violatingRuleNames) > 1 ? 's' : '',
                implode(', ', $violatingRuleNames)
            )
        );

        $this->assertSame($expectedViolationCount, $renderer->getTotalViolationCount(), 'Found more violations then expected. Every file inside the "InValid" directory should have exact one violation.');
    }

    /**
     * @return string
     */
    protected function getRuleSetPath()
    {
        $ruleSet = $this->getSourceDirectory() . '/ruleset.xml';

        return $ruleSet;
    }

    /**
     * @return string
     */
    protected function getSourceDirectory()
    {
        return __DIR__ . '/../src/';
    }

    /**
     * @return TextRenderer
     */
    protected function getRenderer()
    {
        $renderer = new TextRenderer();
        $writer = new StreamWriter(STDOUT);
        $renderer->setWriter($writer);

        return $renderer;
    }

    /**
     * @param \ArchitectureSniffer\AbstractArchitectureSnifferTestCase $testCase
     *
     * @return string
     */
    private function getRuleName(AbstractArchitectureSnifferTestCase $testCase)
    {
        $className = substr(get_class($testCase), 0, -4);
        $classNameParts = explode('\\', $className);

        return array_pop($classNameParts);
    }
}

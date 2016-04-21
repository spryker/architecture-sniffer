<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer;

use PHPMD\AbstractRenderer;
use PHPMD\Report;
use PHPMD\RuleViolation;

class TextRenderer extends \PHPMD\Renderer\TextRenderer
{

    /**
     * @var string
     */
    private $currentRuleName;

    /**
     * @var array
     */
    private $otherViolatingRuleNames = [];

    /**
     * @var int
     */
    private $totalViolationCount = 0;

    /**
     * @param string $ruleName
     *
     * @return void
     */
    public function setCurrentRuleName($ruleName)
    {
        $this->currentRuleName = $ruleName;
    }

    /**
     * @param \PHPMD\Report $report
     *
     * @return void
     */
    public function renderReport(Report $report)
    {
        $writer = $this->getWriter();
        $violations = $report->getRuleViolations();
        $this->totalViolationCount = count($violations);

        foreach ($violations as $violation) {

            $ruleName = $violation->getRule()->getName();
            if ($this->currentRuleName && $ruleName === $this->currentRuleName) {
                continue;
            }

            $this->otherViolatingRuleNames[] = $ruleName;

            $writer->write(PHP_EOL);
            $writer->write(basename($violation->getFileName()));
            $writer->write(':');
            $writer->write($violation->getBeginLine());
            $writer->write("\t");
            $writer->write($violation->getDescription());
            $writer->write(PHP_EOL);
        }

        foreach ($report->getErrors() as $error) {
            $writer->write($error->getFile());
            $writer->write("\t-\t");
            $writer->write($error->getMessage());
            $writer->write(PHP_EOL);
        }
    }

    /**
     * @return array
     */
    public function getOtherViolatingRuleNames()
    {
        return $this->otherViolatingRuleNames;
    }

    /**
     * @return int
     */
    public function getTotalViolationCount()
    {
        return $this->totalViolationCount;
    }

}

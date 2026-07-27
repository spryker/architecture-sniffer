<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Console\Formatter;

class ProjectResultsFormatter
{
    /**
     * @var array<int, string>
     */
    protected const array PRIORITY_MAP = [
        1 => 'CRITICAL',
        2 => 'MAJOR',
        3 => 'MEDIUM',
        4 => 'MINOR',
        5 => 'CORE',
    ];

    protected const string GROUP_SPRYKER = 'SPRYKER ARCHITECTURE RULES';

    protected const string GROUP_GENERAL = 'GENERAL RULES';

    /**
     * Formats a decoded phpmd JSON report into a grouped, human-readable summary.
     *
     * @param array<string, mixed> $results
     *
     * @return string
     */
    public function format(array $results): string
    {
        $fileOutput = '';

        foreach ($this->groupViolations($results) as $group => $convertedResult) {
            $fileOutput .= $this->formatGroup($group, $convertedResult);
        }

        return $fileOutput;
    }

    /**
     * @param string $group
     * @param array<int, array<string, array<string, array<int, array<string, mixed>>>>> $convertedResult
     *
     * @return string
     */
    protected function formatGroup(string $group, array $convertedResult): string
    {
        ksort($convertedResult);

        $output = sprintf('=================================== %s ===================================%s%s', $group, PHP_EOL, PHP_EOL);
        $totalInGroup = 0;

        foreach ($convertedResult as $priorityIndex => $rulesetGroup) {
            [$priorityOutput, $totalInPriority] = $this->formatPriority($priorityIndex, $rulesetGroup);
            $output .= $priorityOutput;
            $totalInGroup += $totalInPriority;
        }

        return $output . $this->formatTotal($group, $totalInGroup);
    }

    /**
     * @param int $priorityIndex
     * @param array<string, array<string, array<int, array<string, mixed>>>> $rulesetGroup
     *
     * @return array{0: string, 1: int}
     */
    protected function formatPriority(int $priorityIndex, array $rulesetGroup): array
    {
        $priorityValue = static::PRIORITY_MAP[$priorityIndex];

        $output = sprintf('========== %s:%s%s', $priorityValue, PHP_EOL, PHP_EOL);
        $totalInPriority = 0;

        foreach ($rulesetGroup as $ruleSetName => $ruleGroup) {
            [$ruleSetOutput, $totalInRuleSet] = $this->formatRuleSet($ruleSetName, $ruleGroup);
            $output .= $ruleSetOutput;
            $totalInPriority += $totalInRuleSet;
        }

        return [$output . $this->formatTotal($priorityValue, $totalInPriority), $totalInPriority];
    }

    /**
     * @param string $ruleSetName
     * @param array<string, array<int, array<string, mixed>>> $ruleGroup
     *
     * @return array{0: string, 1: int}
     */
    protected function formatRuleSet(string $ruleSetName, array $ruleGroup): array
    {
        $output = sprintf('===== %s:%s%s', $ruleSetName, PHP_EOL, PHP_EOL);
        $totalInRuleSet = 0;

        foreach ($ruleGroup as $ruleName => $violations) {
            [$ruleOutput, $totalInRuleName] = $this->formatRule($ruleName, $violations);
            $output .= $ruleOutput;
            $totalInRuleSet += $totalInRuleName;
        }

        return [$output . $this->formatTotal($ruleSetName, $totalInRuleSet), $totalInRuleSet];
    }

    /**
     * @param string $ruleName
     * @param array<int, array<string, mixed>> $violations
     *
     * @return array{0: string, 1: int}
     */
    protected function formatRule(string $ruleName, array $violations): array
    {
        $output = sprintf('= %s:%s', $ruleName, PHP_EOL);

        foreach ($violations as $violation) {
            $output .= sprintf(
                '- %s:%s - %s%s',
                $violation['file'],
                $violation['violation']['beginLine'],
                $violation['violation']['description'],
                PHP_EOL,
            );
        }

        return [$output . $this->formatTotal($ruleName, count($violations)), count($violations)];
    }

    protected function formatTotal(string $label, int $total): string
    {
        return sprintf('TOTAL violations for %s %d%s%s', $label, $total, PHP_EOL, PHP_EOL);
    }

    /**
     * Groups violations by group => priority => ruleSet => rule.
     *
     * @param array<string, mixed> $results
     *
     * @return array<string, array<int, array<string, array<string, array<int, array<string, mixed>>>>>
     */
    protected function groupViolations(array $results): array
    {
        $convertedResults = [];

        foreach ($results['files'] as $fileResults) {
            foreach ($fileResults['violations'] as $violation) {
                $priorityKey = $violation['priority'];
                $ruleSetKey = $violation['ruleSet'];

                $group = str_contains($ruleSetKey, 'Spryker') ? static::GROUP_SPRYKER : static::GROUP_GENERAL;

                $ruleKey = $violation['rule'];

                $convertedResults[$group][$priorityKey][$ruleSetKey][$ruleKey][] = [
                    'file' => $fileResults['file'],
                    'violation' => $violation,
                ];
            }
        }

        return $convertedResults;
    }
}

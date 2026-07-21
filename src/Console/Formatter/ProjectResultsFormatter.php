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
    protected const PRIORITY_MAP = [
        1 => 'CRITICAL',
        2 => 'MAJOR',
        3 => 'MEDIUM',
        4 => 'MINOR',
        5 => 'CORE',
    ];

    /**
     * @var string
     */
    protected const GROUP_SPRYKER = 'SPRYKER ARCHITECTURE RULES';

    /**
     * @var string
     */
    protected const GROUP_GENERAL = 'GENERAL RULES';

    /**
     * Formats a decoded phpmd JSON report into a grouped, human-readable summary.
     *
     * @param array<string, mixed> $results
     *
     * @return string
     */
    public function format(array $results): string
    {
        $convertedResults = $this->groupViolations($results);

        $fileOutput = '';
        foreach ($convertedResults as $group => $convertedResult) {
            $fileOutput .= '=================================== ' . $group . ' ===================================' . PHP_EOL . PHP_EOL;

            ksort($convertedResult);

            $totalInGroup = 0;

            foreach ($convertedResult as $priorityIndex => $rulesetGroup) {
                $priorityValue = static::PRIORITY_MAP[$priorityIndex];

                $totalInPriority = 0;

                $fileOutput .= '========== ' . $priorityValue . ':' . PHP_EOL . PHP_EOL;

                foreach ($rulesetGroup as $ruleSetName => $ruleGroup) {
                    $totalInRuleSet = 0;

                    $fileOutput .= '===== ' . $ruleSetName . ':' . PHP_EOL . PHP_EOL;

                    foreach ($ruleGroup as $ruleName => $violations) {
                        $totalInRuleName = 0;

                        $fileOutput .= '= ' . $ruleName . ':' . PHP_EOL;

                        foreach ($violations as $violation) {
                            $fileOutput .= '- ' . $violation['file'] . ':' . $violation['violation']['beginLine'] . ' - ' . $violation['violation']['description'] . PHP_EOL;
                            $totalInGroup++;
                            $totalInPriority++;
                            $totalInRuleSet++;
                            $totalInRuleName++;
                        }
                        $fileOutput .= 'TOTAL violations for ' . $ruleName . ' ' . $totalInRuleName . PHP_EOL . PHP_EOL;
                    }

                    $fileOutput .= 'TOTAL violations for ' . $ruleSetName . ' ' . $totalInRuleSet . PHP_EOL . PHP_EOL;
                }

                $fileOutput .= 'TOTAL violations for ' . $priorityValue . ' ' . $totalInPriority . PHP_EOL . PHP_EOL;
            }

            $fileOutput .= 'TOTAL violations for ' . $group . ' ' . $totalInGroup . PHP_EOL . PHP_EOL;
        }

        return $fileOutput;
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

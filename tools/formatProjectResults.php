<?php

/**
 * Formats a phpmd JSON report into a grouped, human-readable summary.
 *
 * Usage:
 *   php vendor/spryker/architecture-sniffer/tools/formatProjectResults.php results.json
 *
 * Generate the JSON report with, e.g.:
 *   vendor/bin/phpmd src/Pyz json vendor/spryker/architecture-sniffer/src/Project/ruleset.xml \
 *       --minimumpriority 3 --baseline-file phpmd.project.baseline.xml > results.json
 */

const PRIORITY_MAP = [
    1 => 'CRITICAL',
    2 => 'MAJOR',
    3 => 'MEDIUM',
    4 => 'MINOR',
    5 => 'CORE',
];

$inputFile = $argv[1] ?? 'results.json';

if (!is_file($inputFile)) {
    fwrite(STDERR, sprintf('Report file not found: %s%s', $inputFile, PHP_EOL));
    fwrite(STDERR, sprintf('Usage: php %s <results.json>%s', basename(__FILE__), PHP_EOL));

    exit(1);
}

$file = file_get_contents($inputFile);
$results = json_decode($file, true);

if (!is_array($results) || !isset($results['files'])) {
    fwrite(STDERR, sprintf('Invalid or empty phpmd JSON report: %s%s', $inputFile, PHP_EOL));

    exit(1);
}

$convertedResults = [];

foreach ($results['files'] as $fileResults) {
    foreach ($fileResults['violations'] as $violation) {
        $priorityKey = $violation['priority'];
        $ruleSetKey = $violation['ruleSet'];

        $group = str_contains($ruleSetKey, 'Spryker') ? 'SPRYKER ARCHITECTURE RULES' : 'GENERAL RULES';

        $ruleKey = $violation['rule'];

        $convertedResults[$group][$priorityKey][$ruleSetKey][$ruleKey][] = [
            'file' => $fileResults['file'],
            'violation' => $violation,
        ];
    }
}

$fileOutput = '';
foreach ($convertedResults as $group => $convertedResult) {
    $fileOutput .= '=================================== ' . $group . ' ===================================' . PHP_EOL . PHP_EOL;

    ksort($convertedResult);

    $totalInGroup = 0;

    foreach ($convertedResult as $priorityIndex => $rulesetGroup) {
        $priorityValue = PRIORITY_MAP[$priorityIndex];

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

echo $fileOutput;

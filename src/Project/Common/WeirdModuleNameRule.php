<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class WeirdModuleNameRule extends AbstractRule implements ClassAware
{
    public const string RULE = 'Module name should not contain any configured weird words.';

    /**
     * Name of the configurable property holding a comma-separated list of module names
     * to exclude from this rule (e.g. legacy example modules that cannot be renamed).
     */
    protected const string PROPERTY_MODULE_EXCLUDE_LIST = 'moduleexcludelist';

    /**
     * Name of the configurable property holding a comma-separated list of weird words.
     */
    protected const string PROPERTY_WEIRD_WORDS = 'weirdwords';

    /**
     * Default comma-separated list of weird words.
     */
    protected const string DEFAULT_WEIRD_WORDS = 'test,dummy,example,antelope';

    public function getDescription(): string
    {
        return static::RULE;
    }

    public function apply(AbstractNode $node): void
    {
        $classFullName = $node->getFullQualifiedName();

        $weirdWords = $this->getWeirdWords();

        if ($weirdWords === []) {
            return;
        }

        if (preg_match($this->buildWeirdModuleNamePattern($weirdWords), $classFullName, $matches) === 0) {
            return;
        }

        $moduleName = $matches[1] ?? '';

        if ($this->isModuleExcluded($moduleName)) {
            return;
        }

        $this->addViolation(
            $node,
            [
                sprintf(
                    'Module name %s should not contain weird words: %s.',
                    $moduleName,
                    implode('|', $weirdWords),
                ),
            ],
        );
    }

    /**
     * @param array<int, string> $weirdWords
     *
     * @return string
     */
    protected function buildWeirdModuleNamePattern(array $weirdWords): string
    {
        $alternation = implode('|', array_map('preg_quote', $weirdWords));

        return sprintf('#^[\w]+\\\\(?:Zed|Client|Yves|Glue|Service|Shared)\\\\([\w]*(?i:%s)[\w]*)\\\\.+#', $alternation);
    }

    /**
     * @return array<int, string>
     */
    protected function getWeirdWords(): array
    {
        $rawWeirdWords = trim((string)$this->getStringProperty(static::PROPERTY_WEIRD_WORDS, static::DEFAULT_WEIRD_WORDS));

        if ($rawWeirdWords === '') {
            return [];
        }

        $weirdWords = array_map('trim', explode(',', $rawWeirdWords));

        return array_values(array_filter($weirdWords, static fn (string $weirdWord): bool => $weirdWord !== ''));
    }

    protected function isModuleExcluded(string $moduleName): bool
    {
        if ($moduleName === '') {
            return false;
        }

        return in_array($moduleName, $this->getExcludedModuleNames(), true);
    }

    /**
     * @return array<int, string>
     */
    protected function getExcludedModuleNames(): array
    {
        $rawExcludeList = trim((string)$this->getStringProperty(static::PROPERTY_MODULE_EXCLUDE_LIST, ''));

        if ($rawExcludeList === '') {
            return [];
        }

        $moduleNames = array_map('trim', explode(',', $rawExcludeList));

        return array_values(array_filter($moduleNames, static fn (string $moduleName): bool => $moduleName !== ''));
    }
}

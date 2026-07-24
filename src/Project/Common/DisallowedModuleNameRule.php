<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class DisallowedModuleNameRule extends AbstractRule implements ClassAware
{
    public const string RULE = 'Module name should not contain any configured disallowed words.';

    /**
     * Name of the configurable property holding a comma-separated list of module names
     * to exclude from this rule (e.g. legacy example modules that cannot be renamed).
     */
    protected const string PROPERTY_MODULE_EXCLUDE_LIST = 'moduleexcludelist';

    /**
     * Name of the configurable property holding a comma-separated list of disallowed words.
     */
    protected const string PROPERTY_DISALLOWED_WORDS = 'disallowedwords';

    /**
     * Default comma-separated list of disallowed words.
     */
    protected const string DEFAULT_DISALLOWED_WORDS = 'test,dummy,example,antelope';

    public function getDescription(): string
    {
        return static::RULE;
    }

    public function apply(AbstractNode $node): void
    {
        $classFullName = $node->getFullQualifiedName();

        $disallowedWords = $this->getDisallowedWords();

        if ($disallowedWords === []) {
            return;
        }

        if (preg_match($this->buildDisallowedModuleNamePattern($disallowedWords), $classFullName, $matches) === 0) {
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
                    'Module name %s should not contain disallowed words: %s.',
                    $moduleName,
                    implode('|', $disallowedWords),
                ),
            ],
        );
    }

    /**
     * @param array<int, string> $disallowedWords
     *
     * @return string
     */
    protected function buildDisallowedModuleNamePattern(array $disallowedWords): string
    {
        $alternation = implode('|', array_map('preg_quote', $disallowedWords));

        return sprintf('#^[\w]+\\\\(?:Zed|Client|Yves|Glue|Service|Shared)\\\\([\w]*(?i:%s)[\w]*)\\\\.+#', $alternation);
    }

    /**
     * @return array<int, string>
     */
    protected function getDisallowedWords(): array
    {
        $rawDisallowedWords = trim((string)$this->getStringProperty(static::PROPERTY_DISALLOWED_WORDS, static::DEFAULT_DISALLOWED_WORDS));

        if ($rawDisallowedWords === '') {
            return [];
        }

        $disallowedWords = array_map('trim', explode(',', $rawDisallowedWords));

        return array_values(array_filter($disallowedWords, static fn (string $disallowedWord): bool => $disallowedWord !== ''));
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

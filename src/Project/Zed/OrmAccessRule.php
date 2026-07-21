<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Zed;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

class OrmAccessRule extends AbstractRule implements ClassAware
{
    /**
     * @var string
     */
    protected const RULE = 'Defines rules of calls: No call from Orm Query to Zed Business, No call from Orm Entity to Zed Business.';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return static::RULE;
    }

    /**
     * @var array
     */
    protected $patterns = [
        [
            '(^[\w]+\\\\Zed\\\\(?!.*(?:DataImport|Storage|Search))[\w]+\\\\Business\\\\.+)',
            '(^Orm\\\\Zed\\\\[\w]+\\\\Persistence\\\\[\w]+Query)',
            '{type} {source} accesses {target} which violates rule "No call from Orm Query to Zed Business"',
        ],
        [
            '(^[\w]+\\\\Zed\\\\(?!.*(?:DataImport|Storage|Search))[\w]+\\\\Business\\\\.+)',
            '(^Orm\\\\Zed\\\\[\w]+\\\\Persistence\\\\(?!.*(?:(Query|TableMap))))',
            '{type} {source} accesses {target} which violates rule "No call from Orm Entity to Zed Business"',
        ],
    ];

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        $ignoreClassPattern = $this->getStringProperty('ignoreclasspattern', '');

        if ($ignoreClassPattern !== '' && preg_match($ignoreClassPattern, $node->getFullQualifiedName()) === 1) {
            return;
        }

        $patterns = $this->collectPatterns($node);

        $this->applyPatterns($node, $patterns);

        foreach ($node->getMethods() as $method) {
            $this->applyPatterns(
                $method,
                $patterns,
            );
        }
    }

    /**
     * @param \PHPMD\AbstractNode $node
     * @param array<string> $patterns
     *
     * @return void
     */
    protected function applyPatterns(AbstractNode $node, array $patterns)
    {
        if ($node->hasSuppressWarningsAnnotationFor($this)) {
            return;
        }

        foreach ($node->getDependencies() as $dependency) {
            $targetQName = sprintf('%s\\%s', $dependency->getNamespaceName(), $dependency->getName());

            foreach ($patterns as [$srcPattern, $targetPattern, $message]) {
                if (preg_match($srcPattern, $node->getFullQualifiedName()) === 0) {
                    continue;
                }
                if (preg_match($targetPattern, $targetQName) === 0) {
                    continue;
                }

                $this->addViolation(
                    $node,
                    [
                        str_replace(
                            ['{type}', '{source}', '{target}'],
                            [ucfirst($node->getType()), $node->getFullQualifiedName(), $targetQName],
                            $message,
                        ),
                    ],
                );
            }
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode $class
     *
     * @return array<string>
     */
    protected function collectPatterns(ClassNode $class): array
    {
        $patterns = [];
        foreach ($this->patterns as [$srcPattern, $targetPattern, $message]) {
            if (preg_match($srcPattern, $class->getNamespaceName())) {
                $patterns[] = [$srcPattern, $targetPattern, $message];
            }
        }

        return $patterns;
    }
}

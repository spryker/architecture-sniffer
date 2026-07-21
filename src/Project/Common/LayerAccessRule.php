<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

/**
 * Project-strict variant of the layer-access rule; intentionally kept distinct from ArchitectureSniffer\Common\LayerAccessRule (NFR-3 documented exception).
 */
class LayerAccessRule extends AbstractRule implements ClassAware
{
    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Some layers must not call other layers:'
            . ' No call from Yves to Zed|Glue'
            . ', No call from Client to Zed|Glue|Yves'
            . ', No call from Glue to Yves'
            . ', No call from Glue to Zed Persistence|Presentation'
            . ', No call from Glue to Orm\Zed'
            . ', No call from Shared to Zed|Client|Yves|Glue|Service'
            . ', No call from Service to Zed|Client|Yves|Glue'
            . ', No call from Zed to Yves|Glue'
            . ', No call from Zed Business to Zed Presentation'
            . ', No call from Zed Communication to Zed Presentation'
            . ', No call from Zed Persistence to Zed Business|Communication|Presentation'
            . ', No call from Zed Persistence to Client'
            . ', No call from Zed Presentation to Zed|Client|Yves|Glue|Service|Shared.';
    }

    /**
     * @var array
     */
    protected $patterns = [

        // Yves
        [
            '(^[\w]+\\\\Yves\\\\.+)',
            '(^[\w]+\\\\(Zed|Glue)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Yves to Zed|Glue"',
        ],

        // Client
        [
            '(^[\w]+\\\\Client\\\\.+)',
            '(^[\w]+\\\\(Zed|Glue|Yves)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Client to Zed|Glue|Yves"',
        ],

        // Glue
        [
            '(^[\w]+\\\\Glue\\\\.+)',
            '(^[\w]+\\\\Yves\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Glue to Yves"',
        ],
        [
            '(^[\w]+\\\\Glue\\\\.+)',
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\(Persistence|Presentation)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Glue to Zed Persistence|Presentation"',
        ],
        [
            '(^[\w]+\\\\Glue\\\\.+)',
            '(^Orm\\\\Zed\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Glue to Orm\Zed"',
        ],

        // Shared
        [
            '(^[\w]+\\\\Shared\\\\.+)',
            '(^[\w]+\\\\(Zed|Client|Yves|Glue|Service)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Shared to Zed|Client|Yves|Glue|Service"',
        ],

        // Service
        [
            '(^[\w]+\\\\Service\\\\.+)',
            '(^[\w]+\\\\(Zed|Client|Yves|Glue)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Service to Zed|Client|Yves|Glue"',
        ],

        // Zed
        [
            '(^[\w]+\\\\Zed\\\\.+)',
            '(^[\w]+\\\\(Yves|Glue)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed to Yves|Glue"',
        ],

        // Zed Business
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Business\\\\.+)',
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Presentation\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Business to Zed Presentation"',
        ],

        // Zed Communication
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Communication\\\\.+)',
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Presentation\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Communication to Zed Presentation"',
        ],

        // Zed Persistence
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Persistence\\\\.+)',
            '(.+\\\\Zed\\\\[\w]+\\\\(Business|Communication|Presentation)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Persistence to Zed Business|Communication|Presentation"',
        ],
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Persistence\\\\.+)',
            '(^[\w]+\\\\Client\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Persistence to Client"',
        ],

        // Zed Presentation
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Presentation\\\\.+)',
            '(^[\w]+\\\\(Zed|Client|Yves|Glue|Service|Shared)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Presentation to Zed|Client|Yves|Glue|Service|Shared"',
        ],
    ];

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
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

        $ignoreDependencyPattern = $this->getStringProperty('ignoredependencypattern', '');

        foreach ($node->getDependencies() as $dependency) {
            $targetQName = sprintf('%s\\%s', $dependency->getNamespaceName(), $dependency->getName());

            if ($ignoreDependencyPattern !== '' && preg_match($ignoreDependencyPattern, $targetQName) === 1) {
                continue;
            }

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

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
            . ' No call from Zed|Glue to Yves'
            . ', No call from Glue|Yves|Zed to Client'
            . ', No call from Yves to Glue'
            . ', No call from Zed Persistence|Presentation to Glue'
            . ', No call from Zed Persistence|Presentation to Glue'
            . ', No call from Zed|Client|Yves|Glue|Service to Shared'
            . ', No call from Zed|Client|Yves|Glue to Service'
            . ', No call from Yves|Glue to Zed'
            . ', No call from Zed Presentation to Zed Business'
            . ', No call from Zed Presentation to Zed Communication'
            . ', No call from Zed Business|Communication|Presentation to Zed Persistence'
            . ', No call from Client to Zed Persistence'
            . ', No call from Zed|Client|Yves|Glue|Service|Shared to Zed Presentation.';
    }

    /**
     * @var array
     */
    protected $patterns = [

        // Yves
        [
            '(^[\w]+\\\\Yves\\\\.+)',
            '(^[\w]+\\\\(Zed|Glue)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed|Glue to Yves"',
        ],

        // Client
        [
            '(^[\w]+\\\\Client\\\\.+)',
            '(^[\w]+\\\\(Zed|Glue|Yves)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Glue|Yves|Zed to Client"',
        ],

        // Glue
        [
            '(^[\w]+\\\\Glue\\\\.+)',
            '(^[\w]+\\\\Yves\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Yves to Glue"',
        ],
        [
            '(^[\w]+\\\\Glue\\\\.+)',
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\(Persistence|Presentation)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Persistence|Presentation to Glue"',
        ],
        [
            '(^[\w]+\\\\Glue\\\\.+)',
            '(^Orm\\\\Zed\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Persistence|Presentation to Glue"',
        ],

        // Shared
        [
            '(^[\w]+\\\\Shared\\\\.+)',
            '(^[\w]+\\\\(Zed|Client|Yves|Glue|Service)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed|Client|Yves|Glue|Service to Shared"',
        ],

        // Service
        [
            '(^[\w]+\\\\Service\\\\.+)',
            '(^[\w]+\\\\(Zed|Client|Yves|Glue)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed|Client|Yves|Glue to Service"',
        ],

        // Zed
        [
            '(^[\w]+\\\\Zed\\\\.+)',
            '(^[\w]+\\\\(Yves|Glue)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Yves|Glue to Zed"',
        ],

        // Zed Business
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Business\\\\.+)',
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Presentation\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Presentation to Zed Business"',
        ],

        // Zed Communication
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Communication\\\\.+)',
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Presentation\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Presentation to Zed Communication"',
        ],

        // Zed Persistence
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Persistence\\\\.+)',
            '(.+\\\\Zed\\\\[\w]+\\\\(Business|Communication|Presentation)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed Business|Communication|Presentation to Zed Persistence"',
        ],
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Persistence\\\\.+)',
            '(^[\w]+\\\\Client\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Client to Zed Persistence"',
        ],

        // Zed Presentation
        [
            '(^[\w]+\\\\Zed\\\\[\w]+\\\\Presentation\\\\.+)',
            '(^[\w]+\\\\(Zed|Client|Yves|Glue|Service|Shared)\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed|Client|Yves|Glue|Service|Shared to Zed Presentation"',
        ],
    ];

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
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

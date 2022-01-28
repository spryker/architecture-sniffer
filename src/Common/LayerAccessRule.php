<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

class LayerAccessRule extends AbstractRule implements ClassAware
{
    use DeprecationTrait;

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Some layers must not call other layers.';
    }

    /**
     * @var array
     */
    protected $patterns = [
        [
            '(^Spryker.+)',
            '(^Pyz.+)',
            '{type} {source} accesses {target} which violates rule "No call from Core to Project"',
        ],
        [
            '(Spryker\\\\Yves\\\\.+)',
            '(Spryker\\\\Zed\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Yves to Zed"',
        ],
        [
            '(Spryker\\\\Yves\\\\.+)',
            '(Orm\\\\Zed\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Yves to Zed"',
        ],
        [
            '(Spryker\\\\Zed\\\\.+)',
            '(Spryker\\\\Yves\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Zed to Yves"',
        ],
        [
            '(Spryker\\\\Shared\\\\.+)',
            '(Spryker\\\\Zed\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Shared to Zed"',
        ],
        [
            '(Spryker\\\\Shared\\\\.+)',
            '(Spryker\\\\Client\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Shared to Client"',
        ],
        [
            '(Spryker\\\\Shared\\\\.+)',
            '(Spryker\\\\Yves\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Shared to Yves"',
        ],
        [
            '(Spryker\\\\(Shared|Yves|Zed)\\\\Library\\\\.+)',
            '(Spryker\\\\(Shared|Yves|Zed)\\\\(?!Library).+)',
            '{type} {source} accesses {target} which violates rule "No call Library bundle to any other bundle"',
        ],
        [
            '(Spryker\\\\Client\\\\.+)',
            '(Spryker\\\\Zed\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Client to Zed"',
        ],
        [
            '(Spryker\\\\Client\\\\.+)',
            '(Spryker\\\\Yves\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Client to Yves"',
        ],
        [
            '(Spryker\\\\Client\\\\.+)',
            '(Orm\\\\Zed\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Client to Zed"',
        ],
        [
            '(Spryker\\\\Service\\\\.+)',
            '(Spryker\\\\Yves\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Service to Yves"',
        ],
        [
            '(Spryker\\\\Service\\\\.+)',
            '(Spryker\\\\Zed\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Service to Zed"',
        ],
        [
            '(Spryker\\\\Service\\\\.+)',
            '(Orm\\\\Zed\\\\.+)',
            '{type} {source} accesses {target} which violates rule "No call from Service to Zed"',
        ],
    ];

    /**
     * @param \PHPMD\AbstractTypeNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if ($this->isClassDeprecated($node)) {
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
     * @param array $patterns
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
     * @return array
     */
    protected function collectPatterns(ClassNode $class)
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Zed;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\MethodAware;

class RepositoryReadOnlyRule extends AbstractRule implements MethodAware
{
    /**
     * @var string
     */
    protected const RULE = 'Repository should not perform save|update|delete DB operations.';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return static::RULE;
    }

    /**
     * @var string
     */
    protected const REPOSITORY_PATTERN = '(^[\w]+\\\\Zed\\\\[\w]+\\\\Persistence\\\\[\w]+(Repository|RepositoryInterface))';

    /**
     * @var array<string>
     */
    protected const RESTRICTED_METHOD_POSTFIX = [
        'save',
        'update',
        'delete',
    ];

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (!$this->isRepository($node)) {
            return;
        }

        foreach ($node->findChildrenOfType('MethodPostfix') as $classUsage) {
            $methodName = $classUsage->getNode()->getImage();

            if (!in_array($methodName, static::RESTRICTED_METHOD_POSTFIX)) {
                continue;
            }

            $this->addViolation(
                $node,
                [
                    sprintf(
                        'The method %s uses "%s" operation that is restricted for Repository pattern. Use Entity Manager!',
                        $node->getName(),
                        $methodName,
                    ),
                ],
            );
        }
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isRepository(AbstractNode $node): bool
    {
        $parent = $node->getNode()->getParent();
        $className = $parent->getNamespaceName() . '\\' . $parent->getName();

        return preg_match(static::REPOSITORY_PATTERN, $className) !== 0;
    }
}

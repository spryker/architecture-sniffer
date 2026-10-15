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
    protected const string RULE = 'Repository should not perform save|update|delete DB operations.';

    protected const string REPOSITORY_PATTERN = '(^[\w]+\\\\Zed\\\\[\w]+\\\\Persistence\\\\[\w]+(Repository|RepositoryInterface))';

    /**
     * @var array<string>
     */
    protected const array RESTRICTED_METHOD_POSTFIX = [
        'save',
        'update',
        'delete',
    ];

    public function getDescription(): string
    {
        return static::RULE;
    }

    public function apply(AbstractNode $node): void
    {
        if (!$this->isRepository($node)) {
            return;
        }

        $restrictedMethods = $this->getRestrictedMethods();

        foreach ($node->findChildrenOfType('MethodPostfix') as $classUsage) {
            $methodName = $classUsage->getNode()->getImage();

            if (!in_array($methodName, $restrictedMethods)) {
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

    protected function isRepository(AbstractNode $node): bool
    {
        $parent = $node->getNode()->getParent();
        $className = $parent->getNamespaceName() . '\\' . $parent->getName();

        $ignoreClassPattern = $this->getStringProperty('ignoreClassPattern', '');

        if ($ignoreClassPattern !== '' && preg_match($ignoreClassPattern, $className) === 1) {
            return false;
        }

        return preg_match(static::REPOSITORY_PATTERN, $className) !== 0;
    }

    /**
     * @return array<string>
     */
    protected function getRestrictedMethods(): array
    {
        $restrictedMethods = $this->getStringProperty('restrictedMethods', 'save,update,delete');

        return array_filter(array_map('trim', explode(',', $restrictedMethods)), static function (string $methodName): bool {
            return $methodName !== '';
        });
    }
}

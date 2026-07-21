<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Zed;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class RestrictedOrmQueryAccessInZedPersistenceRule extends AbstractRule implements ClassAware
{
    /**
     * @var string
     */
    public const RULE = 'Access to the Orm Query in Zed persistence is possible only through the Repository, Entity Manager or Query Container.';

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
    protected const PERSISTENCE_PATTERN = '(^[\w]+\\\\Zed\\\\[\w]+\\\\Persistence\\\\.+)';

    /**
     * @var string
     */
    protected const ALLOWED_PERSISTENCE_PATTERN = '(.+(Repository|EntityManager|QueryContainer|PersistenceFactory)$)';

    /**
     * @var string
     */
    protected const ORM_QUERY_PATTERN = '(^Orm\\\\Zed\\\\[\w]+\\\\Persistence\\\\[\w]+Query$)';

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

        if (!preg_match(static::PERSISTENCE_PATTERN, $node->getFullQualifiedName())) {
            return;
        }

        if (preg_match(static::ALLOWED_PERSISTENCE_PATTERN, $node->getFullQualifiedName())) {
            return;
        }

        $this->applyRule($node);

        foreach ($node->getMethods() as $method) {
            $this->applyRule($method);
        }
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    protected function applyRule(AbstractNode $node): void
    {
        foreach ($node->getDependencies() as $dependency) {
            $targetQName = sprintf('%s\\%s', $dependency->getNamespaceName(), $dependency->getName());

            if (preg_match(static::ORM_QUERY_PATTERN, $targetQName) === 0) {
                continue;
            }

            $this->addViolation(
                $node,
                [
                    sprintf('%s Offending dependency: %s.', static::RULE, $targetQName),
                ],
            );
        }
    }
}

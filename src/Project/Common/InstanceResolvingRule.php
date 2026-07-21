<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class InstanceResolvingRule extends AbstractRule implements ClassAware
{
    /**
     * @var string
     */
    public const RULE = 'Automatically resolved instances must not be initialized directly with "new". Use Dependency Provider and Resolvers.';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return static::RULE;
    }

    /**
     * @var array<string>
     */
    protected const INSTANCE_PATTERNS = [
        '/^(\w+)\\\\\Zed\\\\\w+\\\\Persistence\\\\\w+(Repository|EntityManager|QueryContainer|PersistenceFactory)$/',
        '/^(\w+)\\\\\Zed\\\\\w+\\\\Business\\\\\w+(Facade|BusinessFactory)$/',
        '/^(\w+)\\\\\Zed\\\\\w+\\\\Communication\\\\\w+(CommunicationFactory)$/',
        '/^(\w+)\\\\\Zed\\\\\w+\\\\\w+(Config|DependencyProvider)$/',
        '/^(\w+)\\\\\Client\\\\\w+\\\\\w+(Client|Config|DependencyProvider)$/',
        '/^(\w+)\\\\\Service\\\\\w+\\\\\w+(DependencyProvider|Service)$/',
        '/^(\w+)\\\\\Glue\\\\\w+\\\\\w+(Factory|Config|DependencyProvider)$/',
    ];

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        $ignoreClassRegexp = $this->getStringProperty('ignoreclasspattern', '');

        if ($ignoreClassRegexp !== '' && preg_match($ignoreClassRegexp, $node->getFullQualifiedName()) === 1) {
            return;
        }

        foreach ($node->getMethods() as $methodNode) {
            $methodName = $methodNode->getImage();
            $allocatedExpressions = $methodNode->findChildrenOfType('AllocationExpression');

            foreach ($allocatedExpressions as $expression) {
                if ($expression->getImage() !== 'new') {
                    continue;
                }

                $reference = $expression->getFirstChildOfType('ClassReference');

                if (!$reference) {
                    continue;
                }

                $referenceName = trim($reference->getName(), '\\');

                foreach (static::INSTANCE_PATTERNS as $pattern) {
                    if (preg_match($pattern, $referenceName)) {
                        $message = sprintf(
                            'Instance `%s` is initialized in method `%s`. %s',
                            $referenceName,
                            $methodName,
                            static::RULE,
                        );
                        $this->addViolation($methodNode, [$message]);

                        break;
                    }
                }
            }
        }
    }
}

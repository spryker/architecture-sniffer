<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Relationship;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class DirectRelationshipResolverInterfaceUsageRule extends AbstractRule implements ClassAware
{
    public const string RULE = 'No direct use of the RelationshipResolverInterface use the AbstractRelationshipResolver instead';

    protected const string RELATIONSHIP_RESOLVER_INTERFACE = 'Spryker\\ApiPlatform\\Relationship\\RelationshipResolverInterface';

    protected const string EXEMPT_NAMESPACE_PREFIX = 'Spryker\\ApiPlatform\\';

    public function getDescription(): string
    {
        return static::RULE;
    }

    public function apply(AbstractNode $node): void
    {
        if ($this->isExempt($node)) {
            return;
        }

        if (!$this->implementsRelationshipResolverInterface($node)) {
            return;
        }

        $this->addViolation($node, [static::RULE]);
    }

    protected function isExempt(AbstractNode $node): bool
    {
        $fullQualifiedName = ltrim((string)$node->getFullQualifiedName(), '\\');

        return strpos($fullQualifiedName, static::EXEMPT_NAMESPACE_PREFIX) === 0;
    }

    protected function implementsRelationshipResolverInterface(AbstractNode $node): bool
    {
        foreach ($node->getInterfaceReferences() as $interfaceReference) {
            $image = ltrim((string)$interfaceReference->getImage(), '\\');

            if ($image === static::RELATIONSHIP_RESOLVER_INTERFACE) {
                return true;
            }
        }

        return false;
    }
}

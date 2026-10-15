<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\ApiPlatform\Relationship\Resolver;

use Spryker\ApiPlatform\Relationship\RelationshipResolverInterface;

class SomeInternalResolver implements RelationshipResolverInterface
{
    public function resolve(array $parentResources, array $context): array
    {
        return [];
    }
}

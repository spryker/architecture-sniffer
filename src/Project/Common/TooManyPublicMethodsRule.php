<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\AbstractTypeNode;
use PHPMD\Rule\ClassAware;

class TooManyPublicMethodsRule extends AbstractRule implements ClassAware
{
    public const string RULE = 'Too many public methods.';

    protected string $ignoreMethodRegexp;

    public function getDescription(): string
    {
        return static::RULE;
    }

    /**
     * @param \PHPMD\AbstractNode|\PHPMD\Node\AbstractTypeNode $node
     */
    public function apply(AbstractNode $node): void
    {
        $ignoreClassRegexp = $this->getStringProperty('ignoreClassPattern', '#^$#');

        $fullClassName = $node->getFullQualifiedName();

        if (preg_match($ignoreClassRegexp, $fullClassName)) {
            return;
        }

        $this->ignoreMethodRegexp = $this->getStringProperty('ignoreMethodPattern', '');

        $threshold = $this->getIntProperty('maxMethods', 10);

        $nom = $this->countMethods($node);

        if ($nom <= $threshold) {
            return;
        }

        $this->addViolation(
            $node,
            [
                sprintf(
                    'The %s %s has %s public methods. Consider refactoring to keep number of public methods under %s.',
                    $node->getType(),
                    $node->getName(),
                    $nom,
                    $threshold,
                ),
            ],
        );
    }

    protected function countMethods(AbstractTypeNode $node): int
    {
        $count = 0;
        foreach ($node->getMethods() as $method) {
            if ($method->getNode()->isPublic() && !$this->isIgnoredMethodName($method->getName())) {
                ++$count;
            }
        }

        return $count;
    }

    private function isIgnoredMethodName(string $methodName): bool
    {
        return (bool)$this->ignoreMethodRegexp &&
            preg_match($this->ignoreMethodRegexp, $methodName) === 1;
    }
}

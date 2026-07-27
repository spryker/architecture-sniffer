<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\MethodAware;

class LocatorInDependencyProviderOnlyRule extends AbstractRule implements MethodAware
{
    public const string RULE = 'Locator should be used in Dependency Provider only';

    protected const string LOCATOR_METHOD_NAMES = '/^(getLocator|locator)$/';

    protected const string CLASSES_ALLOWED_TO_USE_LOCATOR = '/DependencyProvider$/';

    public function getDescription(): string
    {
        return static::RULE;
    }

    public function apply(AbstractNode $node): void
    {
        $ignoreClassRegexp = $this->getStringProperty('ignoreClassPattern', '');

        if ($ignoreClassRegexp !== '' && preg_match($ignoreClassRegexp, $node->getParentName()) === 1) {
            return;
        }

        if ($this->isClassAllowedToUseLocator($node) === true) {
            return;
        }

        foreach ($node->findChildrenOfType('MethodPostfix') as $classUsage) {
            $methodName = $classUsage->getNode()->getImage();

            if ($this->isLocator($methodName) === false) {
                continue;
            }

            $this->addViolation(
                $node,
                [
                    sprintf(
                        'The method %s uses Locator. Locator can be used in DependencyProvider only',
                        $node->getName(),
                    ),
                ],
            );

            break;
        }
    }

    protected function isLocator(string $methodName): bool
    {
        if (preg_match(static::LOCATOR_METHOD_NAMES, $methodName)) {
            return true;
        }

        return false;
    }

    protected function isClassAllowedToUseLocator(AbstractNode $node): bool
    {
        if (preg_match(static::CLASSES_ALLOWED_TO_USE_LOCATOR, $node->getParentName())) {
            return true;
        }

        return false;
    }
}

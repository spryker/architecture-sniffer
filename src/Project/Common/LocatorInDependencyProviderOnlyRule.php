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
    /**
     * @var string
     */
    public const RULE = 'Locator should be used in Dependency Provider only';

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
    protected const LOCATOR_METHOD_NAMES = '/^(getLocator|locator)$/';

    /**
     * @var string
     */
    protected const CLASSES_ALLOWED_TO_USE_LOCATOR = '/DependencyProvider$/';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
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
        }
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    protected function isLocator(string $methodName): bool
    {
        if (preg_match(static::LOCATOR_METHOD_NAMES, $methodName)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    protected function isClassAllowedToUseLocator(AbstractNode $node): bool
    {
        if (preg_match(static::CLASSES_ALLOWED_TO_USE_LOCATOR, $node->getParentName())) {
            return true;
        }

        return false;
    }
}

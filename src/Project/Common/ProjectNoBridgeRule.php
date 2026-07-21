<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class ProjectNoBridgeRule extends AbstractRule implements ClassAware
{
    /**
     * @var string
     */
    public const RULE = 'Project should not use and depend on Bridge pattern.';

    /**
     * @var string
     */
    protected const REGEX_BRIDGE = '/\w+Bridge$/';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return static::RULE;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        $ignoreClassPattern = $this->getStringProperty('ignoreclasspattern', '');
        $ignoreDependencyPattern = $this->getStringProperty('ignoredependencypattern', '');

        $fullClassName = $node->getFullQualifiedName();

        $isClassIgnored = $ignoreClassPattern !== '' && preg_match($ignoreClassPattern, $fullClassName) === 1;

        if (!$isClassIgnored && preg_match(static::REGEX_BRIDGE, $fullClassName) === 1) {
            $this->addViolation(
                $node,
                [
                    sprintf('Project should not use bridges: %s.', $fullClassName),
                ],
            );
        }

        foreach ($node->getMethods() as $method) {
            foreach ($method->getDependencies() as $dependency) {
                $targetQName = sprintf('%s\\%s', $dependency->getNamespaceName(), $dependency->getName());

                if ($ignoreDependencyPattern !== '' && preg_match($ignoreDependencyPattern, $targetQName) === 1) {
                    continue;
                }

                if (preg_match(static::REGEX_BRIDGE, $targetQName) === 1) {
                    $this->addViolation(
                        $method,
                        [
                            sprintf('Project should not depend on bridges: %s.', $targetQName),
                        ],
                    );
                }
            }
        }
    }
}

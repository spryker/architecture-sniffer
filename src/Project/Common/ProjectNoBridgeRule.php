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
        if (preg_match('([\w]+Bridge$)', $node->getFullQualifiedName()) !== 0) {
            $this->addViolation(
                $node,
                [
                    sprintf('Project should not use bridges: %s.', $node->getFullQualifiedName()),
                ],
            );
        }

        foreach ($node->getMethods() as $method) {
            foreach ($method->getDependencies() as $dependency) {
                $targetQName = sprintf('%s\\%s', $dependency->getNamespaceName(), $dependency->getName());

                if (preg_match('([\w]+Bridge$)', $targetQName) !== 0) {
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

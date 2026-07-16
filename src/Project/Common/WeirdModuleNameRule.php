<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class WeirdModuleNameRule extends AbstractRule implements ClassAware
{
    /**
     * @var string
     */
    public const RULE = 'Module name should not contain any weird words like test|dummy|example|antelope';

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
    protected const WEIRD_MODULE_NAME_PATTER = '(^[\w]+\\\\(Zed|Client|Yves|Glue|Service|Shared)\\\\[\w]*(?i:test|dummy|example|antelope)[\w]*\\\\.+)';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        $classFullName = $node->getFullQualifiedName();

        if (preg_match(static::WEIRD_MODULE_NAME_PATTER, $classFullName) === 0) {
            return;
        }

        $this->addViolation(
            $node,
            [
                sprintf('Module name %s should not contain weird words: test|dummy|example|antelope.', $classFullName),
            ],
        );
    }
}

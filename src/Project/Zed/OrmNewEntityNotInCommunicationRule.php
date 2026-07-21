<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Project\Zed;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class OrmNewEntityNotInCommunicationRule extends AbstractRule implements ClassAware
{
    /**
     * @var string
     */
    public const RULE = 'Orm Entity can not be initialized in Zed Communication. Use Entity Manager.';

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
    protected const BUSINESS_AND_COMMUNICATION_PATTERN = '(^[\w]+\\\\Zed\\\\[\w]+\\\\Communication\\\\.+)';

    /**
     * @var string
     */
    protected const ORM_ENTITY_PATTERN = '(^Orm\\\\Zed\\\\[\w]+\\\\Persistence\\\\(?!.*(?:(Query|TableMap))))';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (!preg_match(static::BUSINESS_AND_COMMUNICATION_PATTERN, $node->getFullQualifiedName())) {
            return;
        }

        $ignoreClassPattern = $this->getStringProperty('ignoreclasspattern', '');

        if ($ignoreClassPattern !== '' && preg_match($ignoreClassPattern, $node->getFullQualifiedName()) === 1) {
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

                if ($reference === null) {
                    continue;
                }

                $referenceName = trim($reference->getName(), '\\');

                if (preg_match(static::ORM_ENTITY_PATTERN, $referenceName)) {
                    $message = sprintf(
                        'Entity `%s` initialized in method `%s`. %s',
                        $referenceName,
                        $methodName,
                        static::RULE,
                    );
                    $this->addViolation($methodNode, [$message]);
                }
            }
        }
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Shared;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\InterfaceAware;

class ModuleConstantsFormingConstantValuesRule extends AbstractRule implements InterfaceAware
{
    /**
     * @var string
     */
    public const RULE = 'The modules\' *Constants interfaces must only contain constants to be used with env config. Their values must be exactly the same as the const key prefixed with module name.';

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::RULE;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (
            preg_match('([A-Za-z0-9]+Constants$)', $node->getName()) === 0
            || preg_match('(\\\\Shared\\\\)', $node->getNamespaceName()) === 0
        ) {
            return;
        }

        $moduleName = str_replace('Constants', '', $node->getName());

        foreach ($node->findChildrenOfType('ConstantDeclarator') as $constant) {
            /** @var \PDepend\Source\AST\ASTValue|\PHPMD\AbstractNode $constant */
            $value = $constant->getValue()->getValue()->getImage();

            $expectedConstantValue = sprintf(
                "'%s:%s'",
                $this->getUnderscoredConstantName($moduleName),
                $constant->getImage(),
            );

            if ($value === $expectedConstantValue) {
                continue;
            }

            $message = sprintf(
                'The constant value is expected to be `%s` but is `%s`. This violates the rule "%s"',
                $expectedConstantValue,
                is_array($value) ? print_r($value, true) : $value,
                static::RULE,
            );

            $this->addViolation($node, [$message]);
        }
    }

    /**
     * @param string $moduleName
     *
     * @return string
     */
    protected function getUnderscoredConstantName(string $moduleName): string
    {
        return strtoupper(preg_replace('/(?<!^)[A-Z]/', '_$0', $moduleName));
    }
}

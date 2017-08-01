<?php

namespace ArchitectureSniffer\Shared;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\InterfaceAware;

class ModuleConstantsRule extends AbstractRule implements InterfaceAware
{

    const RULE = 'The modules\' *Constants interfaces must only contain constants to be used with env config. Their values must be exactly the same as the const key prefixed with module name.';

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
        if (preg_match('([A-Za-z0-9]+Constants$)', $node->getName()) === 0) {
            return;
        }

        $moduleName = str_replace('Constants', '', $node->getName());

        foreach ($node->findChildrenOfType('ConstantDeclarator') as $constant) {
            $value = $constant->getValue()->getValue();

            $expectedConstantValue = strtoupper($moduleName) . ':' . $constant->getImage();
            if ($value === $expectedConstantValue) {
                continue;
            }

            $message = sprintf(
                'The constant value is expected to be "%s" but is "%s". This violates the rule "%s"',
                $expectedConstantValue,
                $value,
                static::RULE
            );
            $this->addViolation($node, [$message]);
        }
    }

}

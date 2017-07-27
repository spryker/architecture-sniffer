<?php

namespace ArchitectureSniffer\Shared;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\InterfaceAware;

class ModuleConstantsRule extends AbstractRule implements InterfaceAware
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'The modules\' *Constants interfaces must only contain constants to be used with env config. They also must be prefixed with the module name.';
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (0 === preg_match('([A-Za-z0-9]+Constants$)', $node->getName())) {
            return;
        }

        $moduleName = str_replace('Constants', '', $node->getName());

        foreach ($node->findChildrenOfType('ConstantDeclarator') as $constant) {
            $value = $constant->getValue()->getValue();

            $expectedConstantValue = strtoupper($moduleName) . ':' . $constant->getImage();
            if ($expectedConstantValue !== $value) {
                $message = sprintf(
                    'The constant value is expected to be "%s" but is "%s". This violates the rule "Constant values must be exactly the same as the const key prefixed with module name"',
                    $expectedConstantValue,
                    $value
                );
                $this->addViolation($node, [$message]);
            }
        }
    }

}

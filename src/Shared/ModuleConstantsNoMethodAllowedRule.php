<?php

namespace ArchitectureSniffer\Shared;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\InterfaceAware;

class ModuleConstantsNoMethodAllowedRule extends AbstractRule implements InterfaceAware
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'The modules\' *ConstantsInterface interfaces must only contain constants to be used with env config.';
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

        foreach ($node->getMethods() as $method) {
            $this->addViolation(
                $node,
                [
                    sprintf(
                        'Interface %s defines a method %s() which violates rule "Just constants in these interfaces"',
                        $node->getFullQualifiedName(),
                        $method->getName()
                    )
                ]
            );
        }
    }

}

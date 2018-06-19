<?php

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Rule\ClassAware;

class NoClientInFrontendModuleRule extends SprykerAbstractRule implements ClassAware
{
    protected const RULE = 'There should be no Client layer in frontend modules.';

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
        if (preg_match('#^SprykerShop\\\\Client\\\\#', $node->getFullQualifiedName()) === 0) {
            return;
        }

        $this->addViolation(
            $node,
            [
                sprintf(
                    'The %s class is in Client layer which violates rule "' . static::RULE . '"',
                    $node->getFullQualifiedName()
                ),
            ]
        );
    }
}

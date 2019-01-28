<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Shared;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\InterfaceAware;

class ModuleConstantsNoMethodAllowedRule extends AbstractRule implements InterfaceAware
{
    public const RULE = 'The modules\' *Constants interfaces must only contain constants to be used with env config, no methods etc.';

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

        foreach ($node->getMethods() as $method) {
            $this->addViolation(
                $node,
                [
                    sprintf(
                        'Interface %s defines a method %s() which violates rule "%s"',
                        $node->getFullQualifiedName(),
                        $method->getName(),
                        static::RULE
                    ),
                ]
            );
        }
    }
}

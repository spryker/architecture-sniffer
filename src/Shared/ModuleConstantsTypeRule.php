<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Shared;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class ModuleConstantsTypeRule extends AbstractRule implements ClassAware
{
    protected const RULE = 'An environment configuration must be an interface.';

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
        if (preg_match('#[A-Za-z0-9]+Constants$#', $node->getName()) === 0) {
            return;
        }

        $message = sprintf(
            'The environment configuration is not an interface. That violates the rule "%s"',
            static::RULE
        );

        $this->addViolation($node, [$message]);
    }
}

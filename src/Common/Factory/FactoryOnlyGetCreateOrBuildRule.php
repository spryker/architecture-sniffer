<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class FactoryOnlyGetCreateOrBuildRule extends AbstractFactoryRule implements ClassAware
{
    const RULE = 'Factories should only contain get*(), create*() or build*() methods.';

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
        if (!$this->isFactory($node)) {
            return;
        }

        foreach ($node->getMethods() as $method) {
            $this->applyRule($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
    {
        if (0 != preg_match('/^(\_\_|create|get|build).+/', $method->getName())) {
            return;
        }

        $message = sprintf(
            'The factory method %s() violates rule "%s"',
            $method->getName(),
            static::RULE
        );

        $this->addViolation($method, [$message]);
    }
}

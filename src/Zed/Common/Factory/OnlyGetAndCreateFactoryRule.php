<?php

namespace ArchitectureSniffer\Zed\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

/**
 * Factories should only contain get* and create* methods
 */
class OnlyGetAndCreateFactoryRule extends AbstractRule implements ClassAware
{

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (!preg_match('/Zed\\.*\\\(Business|Communication|Persistence)\\Factory$/', $node->getName())) {
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
    private function applyRule(MethodNode $method)
    {
        if (0 != preg_match('/^(create|get).+/', $method->getName())) {
            return;
        }

        $message = sprintf(
            'The factory method %s() violates rule "Only methods named create*() or get*() in factories"',
            $method->getName()
        );

        $this->addViolation($method, [$message]);
    }

}

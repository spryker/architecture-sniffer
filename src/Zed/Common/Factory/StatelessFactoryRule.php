<?php

namespace ArchitectureSniffer\Zed\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

/**
 * Factories shouldn't contain any property. Properties would make the factory stateful.
 */
class StatelessFactoryRule extends AbstractRule implements ClassAware
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

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\ClassNode $class
     *
     * @return void
     */
    private function applyRule(ClassNode $class)
    {
        $count = count($class->getProperties());
        if ($count === 0) {
            return;
        }

        $message = sprintf(
            'The class %s contains %d propert%s which violates rule "Factories are stateless"',
            $class->getFullQualifiedName(),
            $count,
            $count === 1 ? 'y' : 'ies'
        );

        $this->addViolation($class, [$message]);
    }

}

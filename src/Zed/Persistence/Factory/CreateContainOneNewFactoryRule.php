<?php

namespace ArchitectureSniffer\Zed\Persistence\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * Every Facade should only retrieve native types and transfer objects
 */
class CreateContainOneNewFactoryRule extends AbstractFactoryRule implements MethodAware
{

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

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    private function applyRule(MethodNode $method)
    {
        if (substr($method->getName(), 0, 6) !== 'create') {
            return;
        }

        $count = count($method->findChildrenOfType('AllocationExpression'));
        if ($count === 1) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The factory method %s contains %d new statements which violates rule "A create*() method must contain exactly 1 `new` statement."',
                    $method->getFullQualifiedName(),
                    $count
                )
            ]
        );
    }

}

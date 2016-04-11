<?php

namespace ArchitectureSniffer\Zed\Business\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * Every Facade should only retrieve native types and transfer objects
 */
class GetContainNoNewFactoryRule extends AbstractFactoryRule implements MethodAware
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
        if ('get' != substr($method->getName(), 0, 3)) {
            return;
        }

        $count = count($method->findChildrenOfType('AllocationExpression'));
        if ($count === 0) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The factory method %s contains %d new statements which violates rule "A get*() method must not contain a `new` keyword."',
                    $method->getFullQualifiedName(),
                    $count
                )
            ]
        );
    }

}

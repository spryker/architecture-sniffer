<?php

namespace ArchitectureSniffer\Zed\Persistence\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class ZedPersistenceFactoryCreateContainOneNewRule extends AbstractFactoryRule implements MethodAware
{
    const RULE = 'A create*() method in factories must contain exactly 1 `new` statement for instantiation.';

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

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
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
                    'The factory method %s contains %d new statements which violates rule "%s"',
                    $method->getFullQualifiedName(),
                    $count,
                    static::RULE
                ),
            ]
        );
    }
}

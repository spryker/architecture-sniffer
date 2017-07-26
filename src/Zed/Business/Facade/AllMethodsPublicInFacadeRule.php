<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class AllMethodsPublicInFacadeRule extends AbstractFacadeRule implements ClassAware
{

    const RULE = 'A facade must only contain public methods.';

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
        if (!$this->isFacade($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\AbstractNode|\PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    protected function applyRule(ClassNode $node)
    {
        foreach ($node->getMethods() as $method) {
            $this->checkVisibility($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function checkVisibility(MethodNode $method)
    {
        if (!$method->getNode()->isPublic()) {
            $message = sprintf(
                'The method "%s" is not public which violates rule "%s"',
                $method->getFullQualifiedName(),
                static::RULE
            );

            $this->addViolation($method, [$message]);
        }
    }

}

<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class AllMethodsPublicInFacadeRule extends AbstractFacadeRule implements ClassAware
{

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
    private function applyRule(ClassNode $node)
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
    private function checkVisibility(MethodNode $method)
    {
        if (!$method->getNode()->isPublic()) {
            $message = sprintf(
                'The method %s is not public which violates rule "Only public methods in Facade"',
                $method->getFullQualifiedName()
            );

            $this->addViolation($method, [$message]);
        }
    }

}

<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ZedBusinessFacadeRule extends AbstractFacadeRule implements ClassAware
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

        $this->applyStatelessThereAreNoProperties($node);

        foreach ($node->getMethods() as $method) {
            $this->applyNoInstantiationsWithNew($method);
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode $class
     *
     * @return void
     */
    private function applyStatelessThereAreNoProperties(ClassNode $class)
    {
        if (0 === count($class->getProperties())) {
            return;
        }

        $this->addViolation(
            $class,
            [
                sprintf(
                    'The are properties in class %s which violates rule "Stateless, there are no properties"',
                    $class->getFullQualifiedName()
                )
            ]
        );
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    private function applyNoInstantiationsWithNew(MethodNode $method)
    {
        if (0 === count($method->findChildrenOfType('AllocationExpression'))) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The method %s uses "new" to instantiate an object which violates rule "No instantiations with \'new\'"',
                    $method->getFullQualifiedName()
                )
            ]
        );
    }

}

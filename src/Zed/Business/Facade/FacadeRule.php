<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class FacadeRule extends AbstractFacadeRule implements ClassAware
{

    const RULE = 'A facade must not have properties.';

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
    protected function applyStatelessThereAreNoProperties(ClassNode $class)
    {
        if (count($class->getProperties()) === 0) {
            return;
        }

        $this->addViolation(
            $class,
            [
                sprintf(
                    'The are properties in class %s which violates rule "' . static::RULE . '"',
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
    protected function applyNoInstantiationsWithNew(MethodNode $method)
    {
        if (count($method->findChildrenOfType('AllocationExpression')) === 0) {
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

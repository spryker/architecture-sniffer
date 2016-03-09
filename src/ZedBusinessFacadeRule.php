<?php
namespace ArchitectureSniffer;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ZedBusinessFacadeRule extends AbstractRule implements ClassAware
{
    public function apply(AbstractNode $node)
    {
        if (0 === preg_match('(\\\\Zed\\\\.*\\\\Business\\\\.*Facade$)', $node->getFullQualifiedName())) {
            return;
        }

        $this->applyStatelessThereAreNoProperties($node);

        foreach ($node->getMethods() as $method) {
            $this->applyNoInstantiationsWithNew($method);
        }
    }

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

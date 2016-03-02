<?php

use PHPMD\Node\MethodNode;

/**
 * Factory methods that create object instances are prefixed with `create`.
 * If they only return a DependencyProvider value, they must be prefixed with `get`.
 */
class MethodMustStartWithCreateInFactory extends \PHPMD\AbstractRule implements \PHPMD\Rule\MethodAware
{

    /**
     * @inheritdoc
     */
    public function apply(\PHPMD\AbstractNode $node)
    {
        /* @var $node \PHPMD\Node\MethodNode */
        $type = $node->getParentType();

        while ($type) {
            $type = $type->getParentClass();
            if (isset($type) && $type->getNamespaceName()
                === \Spryker\Zed\Kernel\Business\AbstractBusinessFactory::CLASS) {
                $this->check($node);
            }
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $node
     * @return void
     */
    protected function check(MethodNode $node)
    {
        $name = $node->getName();

        if (!$node->isPublic()) {
            return;
        }

        $children = $node->findChildrenOfType('AllocationExpression');

        switch (true) {
            case strpos($name, 'get') === 0 :
                if(count($children) > 0){ // Methode with get*() must have a "new" inside
                    $this->addViolation($node, [$node->getName()]);
                }
                break;
            case strpos($name, 'create') === 0 :
                if(count($children) !== 1){ // Methode with create*() must not have a more than one "new" inside
                    $this->addViolation($node, [$node->getName()]);
                }

                break;
            default:
                $this->addViolation($node, [$node->getName()]);

        }


    }
}

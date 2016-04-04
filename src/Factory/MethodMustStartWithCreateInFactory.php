<?php

namespace ArchitectureSniffer\Factory;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * Factory methods that create object instances are prefixed with `create`.
 * If they only return a DependencyProvider value, they must be prefixed with `get`.
 */
class MethodMustStartWithCreateInFactory extends AbstractRule implements MethodAware
{

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        /** @var \PHPMD\Node\MethodNode $node */
        $type = $node->getParentType();

        while ($type) {
            $type = $type->getParentClass();
            if (isset($type)
                && $type->getNamespaceName() === AbstractBusinessFactory::CLASS) {
                $this->check($node);
            }
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $node
     *
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
            case strpos($name, 'get') === 0:
                if (count($children) > 0) { // Methode with get*() must have a "new" inside
                    $this->addViolation($node, [$node->getName()]);
                }
                break;
            case strpos($name, 'create') === 0:
                if (count($children) !== 1) { // Methode with create*() must not have a more than one "new" inside
                    $this->addViolation($node, [$node->getName()]);
                }
                break;
            default:
                $this->addViolation($node, [$node->getName()]);

        }
    }

}

<?php

namespace ArchitectureSniffer\Common\Factory;

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

        if ($this->isParentCall($method)) {
            return;
        }
        if ($this->isIndirectFactoryMethod($method)) {
            return;
        }

        $methodName = $method->getParentName() . '::' . $method->getName() . '()';
        $className = $method->getFullQualifiedName();

        $message = "{$methodName} contains {$count} new statements which violates rule 'A create*() method must contain exactly 1 `new` statement.'
        $className";

        $this->addViolation($method, [$message]);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function isParentCall(MethodNode $method)
    {
        $primaryPrefixes = $method->findChildrenOfType('MemberPrimaryPrefix');
        if (count($primaryPrefixes) < 1) {
            return false;
        }

        $firstPrimaryPrefix = $primaryPrefixes[0];
        return $firstPrimaryPrefix->getChild(0)->getName() === 'parent';
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return bool
     */
    protected function isIndirectFactoryMethod(MethodNode $method)
    {
        $primaryPrefixes = $method->findChildrenOfType('MemberPrimaryPrefix');
        if (count($primaryPrefixes) < 1) {
            return false;
        }

        if ($primaryPrefixes[0]->getChild(0)->getName() === '$this' && substr($primaryPrefixes[0]->getChild(1)->getName(), 0, 6) === 'create') {
            return true;
        }
        if ($primaryPrefixes[0]->getChild(0)->getName() === '$this' && substr($primaryPrefixes[0]->getChild(1)->getName(), 0, 6) === '->') {
            if (substr($primaryPrefixes[1]->getChild(0)->getName(), 0, 6) === 'create') {
                return true;
            }
        }

        return false;
    }

}
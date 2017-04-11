<?php

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * Every method in a Factory must only return an interface or an array of interfaces
 */
class FactoryMethodReturnInterfaceRule extends AbstractFactoryRule implements MethodAware
{

    const ALLOWED_RETURN_TYPES_PATTERN = '/@return\s(?!((.*)Interface))(.*)/';
    const INVALID_RETURN_TYPE_MATCH = 3;

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
     * @param \PHPMD\Node\MethodNode $node
     *
     * @return void
     */
    private function applyRule(MethodNode $node)
    {
        $comment = $node->getComment();
        if ($this->hasInvalidReturnType($comment)) {

            $class = $node->getParentName();
            $method = $node->getName();
            $fullClassName = $node->getFullQualifiedName();

            $message = "{$class}::{$method}() returns a concrete class which violates the rule 'Factory methods only return interfaces'. 
            {$fullClassName}";

            $this->addViolation($node, [$message]);
        }
    }

    /**
     * @param string $comment
     *
     * @return bool
     */
    private function hasInvalidReturnType($comment)
    {
        if (preg_match(self::ALLOWED_RETURN_TYPES_PATTERN, $comment)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $comment
     *
     * @return bool
     */
    private function getInvalidReturnType($comment)
    {
        if (preg_match(self::ALLOWED_RETURN_TYPES_PATTERN, $comment, $returnType)) {
            return $returnType[self::INVALID_RETURN_TYPE_MATCH];
        }

        return false;
    }

}

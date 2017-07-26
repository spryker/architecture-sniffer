<?php

namespace ArchitectureSniffer\Zed\Business\Factory;

use ArchitectureSniffer\Common\Factory\AbstractFactoryRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class ZedBusinessFactoryMethodReturnInterfaceRule extends AbstractFactoryRule implements MethodAware
{

    const RULE = 'Every method in a Factory must only return an interface or an array of interfaces.';

    const ALLOWED_RETURN_TYPES_PATTERN = '/@return\s(?!((.*)Interface))(.*)/';
    const INVALID_RETURN_TYPE_MATCH = 3;

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
     * @param \PHPMD\Node\MethodNode $node
     *
     * @return void
     */
    protected function applyRule(MethodNode $node)
    {
        $comment = $node->getComment();
        if ($this->hasInvalidReturnType($comment)) {
            $message = sprintf(
                'The %s is using an invalid return type "%s" which violates the rule "%s"',
                $node->getFullQualifiedName(),
                $this->getInvalidReturnType($comment),
                static::RULE
            );

            $this->addViolation($node, [$message]);
        }
    }

    /**
     * @param string $comment
     *
     * @return bool
     */
    protected function hasInvalidReturnType($comment)
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
    protected function getInvalidReturnType($comment)
    {
        if (preg_match(self::ALLOWED_RETURN_TYPES_PATTERN, $comment, $returnType)) {
            return $returnType[self::INVALID_RETURN_TYPE_MATCH];
        }

        return false;
    }

}

<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * Every Facade should only return native types and transfer objects
 */
class ReturnFacadeRule extends AbstractFacadeRule implements MethodAware
{

    const ALLOWED_RETURN_TYPES_PATTERN = '/@return\s(?!void|int|float|integer|string|array|\[\]|.*\[\]|bool|boolean|((.*)Transfer))(.*)/';
    const INVALID_RETURN_TYPE_MATCH = 3;

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
     * @param \PHPMD\Node\MethodNode $node
     *
     * @return void
     */
    private function applyRule(MethodNode $node)
    {
        $comment = $node->getComment();
        if ($this->hasInvalidReturnType($comment)) {
            $message = sprintf(
                'The %s is using an invalid return type "%s" which violates the rule "Should only return native types or transfer objects"',
                $node->getFullQualifiedName(),
                $this->getInvalidReturnType($comment)
            );

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

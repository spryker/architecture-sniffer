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

    const PATTERN = '/@return\s(?!void|int|float|integer|string|array|\[\]|.*\[\]|bool|boolean|((.*)Transfer))(.*)/';

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
                'The %s is using a invalid return type "%s" which violates the rule "Should only return native types or transfer objects"',
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
        if (preg_match(self::PATTERN, $comment)) {
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
        if (preg_match(self::PATTERN, $comment, $returnType)) {
            return $returnType[3];
        }

        return false;
    }

}

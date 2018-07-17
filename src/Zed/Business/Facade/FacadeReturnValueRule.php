<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

class FacadeReturnValueRule extends AbstractFacadeRule implements MethodAware
{
    const RULE = 'Every Facade should only return native types or transfer objects.';

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::RULE;
    }

    const ALLOWED_RETURN_TYPES_PATTERN = '/@return\s(?!void|int|float|integer|string|array|\[\]|.*\[\]|bool|boolean|((.+)Transfer))(.*)/';
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
     * @param \PHPMD\Node\MethodNode|\PDepend\Source\AST\ASTNode $node
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

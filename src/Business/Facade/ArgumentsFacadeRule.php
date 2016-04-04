<?php

namespace ArchitectureSniffer\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * Every Facade should only retrieve native types and transfer objects
 */
class ArgumentsFacadeRule extends AbstractFacadeRule implements MethodAware
{
    const PATTERN = '/@param\s(?!int|integer|string|array|\[\]|bool|boolean|((.*)Transfer))(.*)\s\$/';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        $parent = $node->getNode()->getParent();
        $className = $parent->getNamespaceName() . '\\' . $parent->getName();

        if (!$this->isFacade($className)) {
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
        $invalidTypes = $this->parseArguments($comment);
        if (count($invalidTypes) !== 0) {
            foreach ($invalidTypes as $type) {
                $message = sprintf(
                    'The %s is using a invalid argument type "%s" which violates the rule "Should only retrieve native types and transfer objects"',
                    $node->getFullQualifiedName(),
                    $type
                );

                $this->addViolation($node, [$message]);
            }
        }
    }

    /**
     * @param string $comment
     *
     * @return array
     */
    private function parseArguments($comment)
    {
        $invalidTypes = [];
        if (preg_match_all(self::PATTERN, $comment, $types, PREG_SET_ORDER)) {
            foreach ($types as $type) {
                $invalidTypes[] = $type[3];
            }
        }

        return $invalidTypes;
    }

}

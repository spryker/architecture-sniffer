<?php

namespace ArchitectureSniffer;

use PHPMD\Node\MethodNode;

/**
 * Detects illegal use of Query object.
 */
class QueryMisuse extends \PHPMD\AbstractRule implements \PHPMD\Rule\MethodAware
{

    /**
     * @inheritdoc
     */
    public function apply(\PHPMD\AbstractNode $node)
    {
        /** @var \PHPMD\Node\MethodNode $node */
        $types = $node->findChildrenOfType('MemberPrimaryPrefix');

        foreach ($types as $type) {
            $child0 = $type->getChild(0);

            if ($child0->getName() !== 'queryContainer') {
                continue;
            }

            $childOfChild = $type->getChild(1);

            $childOfChildOfChild = $childOfChild->getChild(0);
            //TODO
        }
    }

    /*
     *     $this->queryContainer->queryGroup()
            ->filterByCreatedAt(null)

            ->find(); // findOne, count
     */

}

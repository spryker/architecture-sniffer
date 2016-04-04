<?php

namespace ArchitectureSniffer\Common;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\MethodAware;

/**
 * Detects illegal use of Query object.
 */
class QueryMisuse extends AbstractRule implements MethodAware
{

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
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

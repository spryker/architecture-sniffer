<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer;

use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;

abstract class SprykerAbstractRule extends AbstractRule implements SprykerRuleInterface
{
    /**
     * @param \PHPMD\Node\MethodNode $node
     *
     * @return \PDepend\Source\AST\ASTMethod
     */
    protected function getNodeFromMethodNode(MethodNode $node)
    {
        return $node->getNode();
    }
}

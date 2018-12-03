<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Bridge;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\InterfaceNode;
use PHPMD\Rule\ClassAware;

class BridgePathRule extends AbstractRule implements ClassAware
{
    const RULE = 'A bridge must lie in "Dependency" folder.';

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
        if (preg_match('([A-Za-z0-9]+Bridge$)', $node->getName()) === 0) {
            return;
        }

        $this->verifyClass($node);
        $this->verifyInterface($node);
    }

    protected function verifyClass($node)
    {
        if (preg_match('#.*\\\\Dependency\\\\.*#', $node->getNamespaceName()) !== 0) {
            return;
        }

        $message = sprintf(
            'The bridge is not lie in "Dependency" folder. That violates the rule "%s"',
            static::RULE
        );

        $this->addViolation($node, [$message]);
    }

    protected function verifyInterface($node)
    {
        $interfaceNode = new InterfaceNode($node->getInterfaces()[0]);

        if (preg_match('#.*\\\\Dependency\\\\.*#', $interfaceNode->getNamespaceName()) !== 0) {
            return;
        }

        $message = sprintf(
            'The bridge interface is not lie in "Dependency" folder. That violates the rule "%s"',
            static::RULE
        );
        $this->addViolation($interfaceNode, [$message]);
    }
}

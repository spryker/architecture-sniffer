<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Bridge;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\InterfaceNode;
use PHPMD\Rule\ClassAware;

class BridgePathRule extends AbstractRule implements ClassAware
{
    /**
     * @var string
     */
    protected const CLASS_RULE = 'A bridge must lie in "Dependency" folder.';

    /**
     * @var string
     */
    protected const INTERFACE_RULE = 'A bridge interface must lie in "Dependency" folder.';

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::CLASS_RULE;
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

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    protected function verifyClass(AbstractNode $node): void
    {
        if (preg_match('#.*\\\\Dependency\\\\.*#', $node->getNamespaceName()) !== 0) {
            return;
        }

        $message = sprintf(
            'The bridge is not in "Dependency" namespace. That violates the rule "%s"',
            static::CLASS_RULE,
        );
        $this->addViolation($node, [$message]);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    protected function verifyInterface(AbstractNode $node): void
    {
        $classNodeInterfaces = $node->getInterfaces();

        if (!$classNodeInterfaces->count()) {
            $message = sprintf(
                'The bridge `%s` doesn\'t  have any interfaces.',
                $node->getName(),
            );
            $this->addViolation($node, [$message]);

            return;
        }

        $firstInterface = $classNodeInterfaces[0];
        $interfaceNode = new InterfaceNode($firstInterface);

        if (preg_match('#.*\\\\Dependency\\\\.*#', $interfaceNode->getNamespaceName()) !== 0) {
            return;
        }

        $message = sprintf(
            'The bridge interface is not in "Dependency" namespace. That violates the rule "%s"',
            static::INTERFACE_RULE,
        );
        $this->addViolation($interfaceNode, [$message]);
    }
}

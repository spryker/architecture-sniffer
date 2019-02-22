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

class BridgeNameRule extends AbstractRule implements ClassAware
{
    protected const CLASS_RULE = 'A bridge name must have \'{source_module_name}To{target_module_name}{layer_name}Bridge\' structure.';
    protected const INTERFACE_RULE = 'A bridge interface name must have \'{source_module_name}To{target_module_name}{layer_name}Interface\' structure.';

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
        if (preg_match('([A-Za-z0-9]+Bridge$)', $node->getName()) === 0 ||
            preg_match('#.*\\\\Dependency\\\\.*#', $node->getNamespaceName()) === 0) {
            return;
        }

        preg_match('#(?<moduleName>[\w]+)\\\\Dependency\\\\(?<layerName>[\w]+)#is', $node->getNamespaceName(), $namespaceParts);

        $this->verifyClass($node, $namespaceParts);
        $this->verifyInterface($node, $namespaceParts);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     * @param array $namespaceParts
     *
     * @return void
     */
    protected function verifyClass(AbstractNode $node, array $namespaceParts): void
    {
        $expectedBridgeNameRegexp = '#^' . $namespaceParts['moduleName'] . 'To[\w]+' . $namespaceParts['layerName'] . 'Bridge$#';

        if (preg_match($expectedBridgeNameRegexp, $node->getName()) !== 0) {
            return;
        }

        $message = sprintf(
            'The bridge name is not \'%sTo{target_module_name}%sBridge\'. That violates the rule "%s"',
            $namespaceParts['moduleName'],
            $namespaceParts['layerName'],
            static::CLASS_RULE
        );

        $this->addViolation($node, [$message]);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     * @param array $namespaceParts
     *
     * @return void
     */
    protected function verifyInterface(AbstractNode $node, array $namespaceParts): void
    {
        $classNodeInterfaces = $node->getInterfaces();

        if (!$classNodeInterfaces->count()) {
            $message = sprintf(
                'The bridge `%s` doesn\'t  have any interfaces.',
                $node->getName()
            );
            $this->addViolation($node, [$message]);

            return;
        }

        $firstInterface = $classNodeInterfaces[0];

        $interfaceNode = new InterfaceNode($firstInterface);

        $expectedBridgeInterfaceNameRegexp = '#^' . $namespaceParts['moduleName'] . 'To[\w]+' . $namespaceParts['layerName'] . 'Interface$#';

        if (preg_match($expectedBridgeInterfaceNameRegexp, $interfaceNode->getName()) === 0) {
            $message = sprintf(
                'The bridge interface name is not \'%sTo{target_module_name}%sInterface\'. That violates the rule "%s"',
                $namespaceParts['moduleName'],
                $namespaceParts['layerName'],
                static::INTERFACE_RULE
            );
            $this->addViolation($interfaceNode, [$message]);

            return;
        }

        if (str_replace('Interface', 'Bridge', $interfaceNode->getName()) !== $node->getName()) {
            $message = 'The bridge interface name must have the same name postfix as the bridge class has.';
            $this->addViolation($interfaceNode, [$message]);

            return;
        }
    }
}

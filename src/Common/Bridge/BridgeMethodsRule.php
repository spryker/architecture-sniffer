<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Bridge;

use PDepend\Source\AST\ASTInterface;
use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\InterfaceNode;
use PHPMD\Rule\ClassAware;

class BridgeMethodsRule extends AbstractRule implements ClassAware
{
    const RULE = 'A bridge name must have \'{target_module_name}To{target_module_name}{layer_name}Bridge\' structure.';

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
        if (preg_match('([A-Za-z0-9]+Bridge$)', $node->getName()) === 0||
            preg_match('#.*\\\\Dependency\\\\.*#', $node->getNamespaceName()) === 0) {
            return;
        }

        $methods = $node->getAllMethods();

        foreach ($methods as $method) {
            if (!$method->isPublic()) {
                continue;
            }

            dd($method->getParent());
        }

        dd($node->getAllMethods());

        $this->verifyClass($node, $namespaceParts);
        $this->verifyInterface($node, $namespaceParts);
    }

    protected function verifyClass($node, $namespaceParts)
    {
        $expectedBridgeNameRegexp = '#^' . $namespaceParts['moduleName'] . 'To[\w]+' . $namespaceParts['layerName'] . 'Bridge$#';

        if (preg_match($expectedBridgeNameRegexp, $node->getName()) !== 0) {
            return;
        }

        $message = sprintf(
            'The bridge name is not \'%sTo{target_module_name}%sBridge\'. That violates the rule "%s"',
            $namespaceParts['moduleName'],
            $namespaceParts['layerName'],
            static::RULE
        );

        $this->addViolation($node, [$message]);
    }

    protected function verifyInterface($node, $namespaceParts)
    {
        $interfaceNode = new InterfaceNode($node->getInterfaces()[0]);

        $expectedBridgeInterfaceNameRegexp = '#^' . $namespaceParts['moduleName'] . 'To[\w]+' . $namespaceParts['layerName'] . 'Interface$#';

        if (preg_match($expectedBridgeInterfaceNameRegexp, $interfaceNode->getName()) === 0) {
            $message = sprintf(
                'The bridge interface name is not \'%sTo{target_module_name}%sInterface\'. That violates the rule "%s"',
                $namespaceParts['moduleName'],
                $namespaceParts['layerName'],
                static::RULE
            );
            $this->addViolation($interfaceNode, [$message]);

            return;
        }


        if (str_replace('Interface', 'Bridge', $interfaceNode->getName()) === $node->getName()) {
            return;
        }

        $message = sprintf(
            'The bridge interface must have the same name postfix.',
            static::RULE
        );
        $this->addViolation($interfaceNode, [$message]);
    }
}

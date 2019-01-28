<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer;

use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;

abstract class SprykerAbstractRule extends AbstractRule implements SprykerRuleInterface
{
    protected const APPLICATION_LAYERS = ['Zed', 'Client', 'Yves', 'Service', 'Shared', 'Glue'];

    /**
     * @param \PHPMD\Node\MethodNode $node
     *
     * @return \PDepend\Source\AST\ASTMethod
     */
    protected function getNodeFromMethodNode(MethodNode $node)
    {
        return $node->getNode();
    }

    /**
     * @param string $namespacedName
     *
     * @return string|null
     */
    protected function getModuleName(string $namespacedName): ?string
    {
        $pattern = sprintf('#(%s)\\\\(?<moduleName>[\w]+)#', implode('|', static::APPLICATION_LAYERS));

        if (preg_match($pattern, $namespacedName, $matches) === 0) {
            return null;
        }

        return $matches['moduleName'];
    }
}

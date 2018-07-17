<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Plugin;

use PHPMD\AbstractNode;
use PHPMD\Rule\InterfaceAware;

class PluginInterfaceSuffixRule extends AbstractPluginRule implements InterfaceAware
{
    protected const EXPECTED_INTERFACE_NAME_SUFFIX = 'PluginInterface';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (!$this->isPlugin($node)) {
            return;
        }

        $className = $node->getName();
        $suffix = substr($className, -strlen(static::EXPECTED_INTERFACE_NAME_SUFFIX));
        if ($suffix !== static::EXPECTED_INTERFACE_NAME_SUFFIX) {
            $this->addViolation($node, [$node->getName()]);
        }
    }
}

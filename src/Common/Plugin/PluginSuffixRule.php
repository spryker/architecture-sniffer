<?php

namespace ArchitectureSniffer\Common\Plugin;

use PHPMD\AbstractNode;
use PHPMD\Rule\ClassAware;

class PluginSuffixRule extends AbstractPluginRule implements ClassAware
{
    protected const EXPECTED_CLASS_NAME_SUFFIX = 'Plugin';

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
        $suffix = substr($className, -strlen(static::EXPECTED_CLASS_NAME_SUFFIX));
        if ($suffix !== static::EXPECTED_CLASS_NAME_SUFFIX) {
            $this->addViolation($node, [$node->getName()]);
        }
    }
}

<?php

namespace ArchitectureSniffer\Common\Plugin;

use PHPMD\AbstractNode;
use PHPMD\Rule\ClassAware;

class PluginSuffixRule extends AbstractPluginRule implements ClassAware
{
    protected const EXPECTED_CLASS_NAME_SUFFIX = 'Plugin';
    protected const EXPECTED_TEST_CLASS_NAME_SUFFIX = 'PluginTest';

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

        if (!$this->isSuffixValid($className)) {
            $this->addViolation($node, [$node->getName()]);
        }
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function isSuffixValid(string $className): bool
    {
        if ($this->isSourcePluginSuffixValid($className)) {
            return true;
        }

        if ($this->isTestPluginSuffixValid($className)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function isSourcePluginSuffixValid(string $className): bool
    {
        $suffix = substr($className, -strlen(static::EXPECTED_CLASS_NAME_SUFFIX));

        return ($suffix === static::EXPECTED_CLASS_NAME_SUFFIX);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function isTestPluginSuffixValid(string $className): bool
    {
        $suffix = substr($className, -strlen(static::EXPECTED_TEST_CLASS_NAME_SUFFIX));

        return ($suffix === static::EXPECTED_TEST_CLASS_NAME_SUFFIX);
    }
}

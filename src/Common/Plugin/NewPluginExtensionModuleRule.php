<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Plugin;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;
use ReflectionClass;

class NewPluginExtensionModuleRule extends AbstractPluginRule implements ClassAware
{
    protected const RULE = 'A plugin class must implement a ModuleExtension\'s interface instead of implementing an extension interface of Module';

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

        $firstInterface = $node->getInterfaces()[0];
        $interfaceReflection = new ReflectionClass($firstInterface->getNamespacedName());

        $this->verifyInterface($node, $interfaceReflection);
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     * @param \ReflectionClass $interfaceReflection
     *
     * @return void
     */
    protected function verifyInterface(ClassNode $node, ReflectionClass $interfaceReflection): void
    {
        $className = $node->getFullQualifiedName();
        $interfaceName = $interfaceReflection->getNamespaceName();

        $classModuleName = $this->getModuleName($className);

        if (!$classModuleName) {
            return;
        }

        $interfaceModuleName = $this->getModuleName($interfaceName);

        if ($classModuleName === $interfaceModuleName) {
            return;
        }

        if (preg_match('#Extension$#', $interfaceModuleName) !== 0) {
            return;
        }

        $this->addViolation(
            $node,
            [
                sprintf(
                    'The plugin class implements %s interface without participation of Extension module which violates rule "%s"',
                    $interfaceName,
                    static::RULE
                ),
            ]
        );
    }
}
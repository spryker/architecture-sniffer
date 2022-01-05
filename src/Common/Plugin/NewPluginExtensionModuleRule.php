<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Plugin;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;
use ReflectionClass;

class NewPluginExtensionModuleRule extends AbstractPluginRule implements ClassAware
{
    /**
     * @var string
     */
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

        $classNodeInterfaces = $node->getInterfaces();

        if (!$classNodeInterfaces->count()) {
            $message = sprintf(
                'The plugin class `%s` doesn\'t have any interfaces. This violates rule "%s"',
                $node->getName(),
                static::RULE,
            );
            $this->addViolation($node, [$message]);

            return;
        }

        $firstInterface = $classNodeInterfaces[0];

        if ($firstInterface === null) {
            return;
        }

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
                    static::RULE,
                ),
            ],
        );
    }
}

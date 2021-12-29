<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

class ImplementsApiInterfaceRule extends SprykerAbstractRule implements ClassAware
{
    /**
     * @var string
     */
    public const RULE = 'Must implement an interface with same name and suffix \'Interface\'.';

    /**
     * @var string
     */
    protected $classRegex = '';

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::RULE;
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (!$this->isApplicable($node)) {
            return;
        }

        $this->applyImplementsInterfaceWithSameNameAndSuffix($node);
    }

    /**
     * @param \PDepend\Source\AST\ASTClass $node
     *
     * @return bool
     */
    protected function isApplicable(AbstractNode $node): bool
    {
        if ($node->isAbstract()) {
            return false;
        }

        if (empty($this->classRegex) || preg_match($this->classRegex, $node->getFullQualifiedName()) === 0) {
            return false;
        }

        return true;
    }

    /**
     * @param \PHPMD\Node\ClassNode|\PDepend\Source\AST\ASTNamespace $class
     *
     * @return void
     */
    protected function applyImplementsInterfaceWithSameNameAndSuffix(ClassNode $class)
    {
        $expectedInterfaceName = sprintf('%sInterface', $class->getImage());

        /** @var \PHPMD\Node\InterfaceNode $interface */
        foreach ($class->getInterfaces() as $interface) {
            if ($interface->getName() === $expectedInterfaceName) {
                return;
            }
        }

        $this->addViolation(
            $class,
            [
                sprintf(
                    'The class %s does not implement an interface %s which violates rule: "' . static::RULE . '"',
                    $class->getFullQualifiedName(),
                    $expectedInterfaceName,
                ),
            ],
        );
    }
}

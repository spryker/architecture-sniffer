<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

class ImplementsApiInterfaceRule extends SprykerAbstractRule implements ClassAware
{
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
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        /** @var \PDepend\Source\AST\ASTClass $node */
        if ($node->isAbstract()) {
            return;
        }

        if (empty($this->classRegex) || preg_match($this->classRegex, $node->getFullQualifiedName()) === 0) {
            return;
        }

        /** @var \PHPMD\Node\ClassNode $node */
        $this->applyImplementsInterfaceWithSameNameAndSuffix($node);
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
                    $expectedInterfaceName
                ),
            ]
        );
    }
}

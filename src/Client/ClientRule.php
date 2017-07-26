<?php

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ClientRule extends SprykerAbstractRule implements ClassAware
{

    const RULE = 'Must implement an interface with same name and suffix \'Interface\'. Every method must also contain the @api tag in docblock and a contract text above.';

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
        /** @var \PHPMD\Node\MethodNode $node */
        if ($node->isAbstract()) {
            return;
        }

        if (0 === preg_match('(\\\\Client\\\\.+Client$)', $node->getFullQualifiedName())) {
            return;
        }

        /** @var \PHPMD\Node\ClassNode $node */
        $this->applyImplementsInterfaceWithSameNameAndSuffix($node);

        foreach ($node->getMethods() as $method) {
            $this->applyEveryPublicMethodMustHaveApiTagAndContractText($method);
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode|\PDepend\Source\AST\ASTNamespace $class
     *
     * @return void
     */
    protected function applyImplementsInterfaceWithSameNameAndSuffix(ClassNode $class)
    {
        $interfaceName = sprintf('%sInterface', $class->getImage());

        /** @var \PHPMD\Node\InterfaceNode $interface */
        foreach ($class->getInterfaces() as $interface) {
            if ($interfaceName === $interface->getName()) {
                return;
            }
        }

        $this->addViolation(
            $class,
            [
                sprintf(
                    'The class %s does not implement an interface %s which violates rule: "' . static::RULE . '"',
                    $class->getFullQualifiedName(),
                    $interfaceName
                )
            ]
        );
    }

    /**
     * @param \PHPMD\Node\MethodNode|\PDepend\Source\AST\ASTMethod $method
     *
     * @return void
     */
    protected function applyEveryPublicMethodMustHaveApiTagAndContractText(MethodNode $method)
    {
        if ($method->isAbstract() || false === $method->isPublic()) {
            return;
        }

        $comment = $method->getComment();
        if (preg_match(
            '(
                \*\s+[{}A-Z0-9\-]+.*\s+
                \*?\s*
                \*\s+@api
            )xi',
            $comment
        )) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The client method %s does not contain an @api tag or contract text ' .
                    'which violates rule: "Every method must have the @api tag in docblock and a contract text above"',
                    $method->getFullQualifiedName()
                )
            ]
        );
    }

}

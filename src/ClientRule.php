<?php
namespace ArchitectureSniffer;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Node\InterfaceNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ClientRule extends AbstractRule implements ClassAware
{
    public function apply(AbstractNode $node)
    {
        if ($node->isAbstract()) {
            return;
        }

        if (0 === preg_match('(\\\\Client\\\\.+Client$)', $node->getFullQualifiedName())) {
            return;
        }

        $this->applyImplementsInterfaceWithSameNameAndSuffix($node);

        foreach ($node->getMethods() as $method) {
            $this->applyEveryPublicMethodMustHaveApiTagAndContractText($method);
        }
    }

    private function applyImplementsInterfaceWithSameNameAndSuffix(ClassNode $class)
    {
        $interfaceName = sprintf('%sInterface', $class->getImage());

        /** @var InterfaceNode $interface */
        foreach ($class->getInterfaces() as $interface) {
            if ($interfaceName === $interface->getName()) {
                return;
            }
        }

        $this->addViolation(
            $class,
            [
                sprintf(
                    'The class %s does not implement an interface %s which violates rule: "Implements an interface with same name and suffix \'Interface\'"',
                    $class->getFullQualifiedName(),
                    $interfaceName
                )
            ]
        );
    }

    private function applyEveryPublicMethodMustHaveApiTagAndContractText(MethodNode $method)
    {
        if ($method->isAbstract() || false === $method->isPublic()) {
            return;
        }

        $comment = $method->getComment();
        if (preg_match(
            '(
                \*\s+[A-Z0-9\-]+.*\s+
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

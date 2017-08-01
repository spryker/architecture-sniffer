<?php

namespace ArchitectureSniffer\Service;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ServiceRule extends AbstractRule implements ClassAware
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
        if ($node->isAbstract()) {
            return;
        }

        if (preg_match('(\\\\Service\\\\.+Service$)', $node->getFullQualifiedName()) === 0) {
            return;
        }

        $this->applyImplementsInterfaceWithSameNameAndSuffix($node);

        foreach ($node->getMethods() as $method) {
            $this->applyEveryPublicMethodMustHaveApiTagAndContractText($method);
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode $class
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
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyEveryPublicMethodMustHaveApiTagAndContractText(MethodNode $method)
    {
        if ($method->isAbstract() || !$method->isPublic()) {
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
                    'The Service method %s does not contain an @api tag or contract text ' .
                    'which violates rule: "Every method must have the @api tag in docblock and a contract text above"',
                    $method->getFullQualifiedName()
                )
            ]
        );
    }

}

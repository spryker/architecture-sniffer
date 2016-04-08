<?php

namespace ArchitectureSniffer\Shared;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\InterfaceAware;

class BundleConstantsRule extends AbstractRule implements InterfaceAware
{

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (0 === preg_match('([A-Za-z0-9]+Constants$)', $node->getName())) {
            return;
        }

        foreach ($node->getMethods() as $method) {
            $this->addViolation(
                $node,
                [
                    sprintf(
                        'Interface %s defines a method %s() which violates rule "Just constants in these interfaces"',
                        $node->getFullQualifiedName(),
                        $method->getName()
                    )
                ]
            );
        }

        foreach ($node->findChildrenOfType('ConstantDeclarator') as $constant) {
            $value = $constant->getValue()->getValue();
            if ($constant->getImage() === $value) {
                continue;
            }

            if (is_array($value)) {
                $value = preg_replace(['([\n\r\s]+)', '( ([\(\)]))'], [' ', '\\1'], var_export($value, true));
            }

            $this->addViolation(
                $node,
                [
                    sprintf(
                        'The value "%s" and the name of constant %s::%s are not equal which violates rule "Only keys no values"',
                        $value,
                        $node->getFullQualifiedName(),
                        $constant->getImage()
                    )
                ]
            );
        }
    }

}

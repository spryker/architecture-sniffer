<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 04.03.16
 * Time: 12:06
 */

namespace ArchitectureSniffer;


use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\InterfaceAware;

class BundleConstantsRule extends AbstractRule implements InterfaceAware
{
    public function apply(AbstractNode $node)
    {
        if (0 === preg_match('([A-Za-z0-9]+Constants$)', $node->getName())) {
            return;
        }

        $qName = sprintf('%s\\%s', $node->getNamespaceName(), $node->getName());
        foreach ($node->getMethods() as $method) {
            $this->addViolation(
                $node,
                [
                    sprintf(
                        'Interface %s defines a method %s() which violates rule "Just constants in these interfaces"',
                        $qName,
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
                        $qName,
                        $constant->getImage()
                    )
                ]
            );
        }
    }
}

<?php
namespace ArchitectureSniffer;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ControllerRule extends AbstractRule implements ClassAware
{
    public function apply(AbstractNode $node)
    {
        if (0 === preg_match('(\\\\[^\\\\]+Controller$)', $node->getFullQualifiedName())) {
            return;
        }

        foreach ($node->getMethods() as $method) {
            $this->applyPublicMethodsHaveActionSuffix($method);
        }
    }

    private function applyPublicMethodsHaveActionSuffix(MethodNode $method)
    {
        if ('Action' === substr($method->getName(), -6, 6)) {
            return;
        }
        if ($method->isProtected() || $method->isPrivate()) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The controller method %s is not suffixed with "Action" which violates rule "All public methods have the suffix *Action"',
                    $method->getFullQualifiedName()
                )
            ]
        );
    }
}

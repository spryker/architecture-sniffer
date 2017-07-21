<?php

namespace ArchitectureSniffer\Zed\Communication\Controller;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ControllerRule extends AbstractRule implements ClassAware
{

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (0 === preg_match('(\\\\[^\\\\]+Controller$)', $node->getFullQualifiedName())) {
            return;
        }
        if ($node->getName() === 'AbstractController') {
            return;
        }

        /** @var \PHPMD\Node\ClassNode $node */
        foreach ($node->getMethods() as $method) {
            $this->applyPublicMethodsHaveActionSuffix($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    private function applyPublicMethodsHaveActionSuffix(MethodNode $method)
    {
        if ('Action' === substr($method->getName(), -6, 6)) {
            return;
        }

        /** @var \PDepend\Source\AST\ASTMethod $method */
        if ($method->isProtected() || $method->isPrivate()) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The controller method %s is not suffixed with "Action" which violates rule "All public methods have the suffix *Action"',
                    $method->getFullQualifiedName()
                ),
            ]
        );
    }

}

<?php

namespace ArchitectureSniffer\Zed\Communication\Controller;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ZedCommunicationControllerRule extends AbstractRule implements ClassAware
{

    const RULE = 'All public controller methods have the suffix `*Action`.';

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
        if (preg_match('(\\\\[^\\\\]+Controller$)', $node->getFullQualifiedName()) === 0) {
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
    protected function applyPublicMethodsHaveActionSuffix(MethodNode $method)
    {
        if (substr($method->getName(), -6, 6) === 'Action') {
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
                    'The controller method %s is not suffixed with "Action" which violates rule "' . static::RULE . '"',
                    $method->getFullQualifiedName()
                ),
            ]
        );
    }

}

<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class NoLogicInFacadeRule extends AbstractFacadeRule implements ClassAware
{

    /**
     * @var array
     */
    private $forbiddenStatements = [
        'foreach',
        'while',
        'for',
        'do',
        'if'
    ];

    /**
     * @param AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (!$this->isFacade($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param AbstractNode|ClassNode $node
     *
     * @return void
     */
    private function applyRule(ClassNode $node)
    {
        foreach ($node->getMethods() as $method) {
            $this->checkStatements($method);
        }
    }

    /**
     * @param MethodNode $method
     *
     * @return void
     */
    private function checkStatements(MethodNode $method)
    {
        foreach ($method->findChildrenOfType('Statement') as $statement) {
            if (!in_array(strtolower($statement->getImage()), $this->forbiddenStatements)) {
                continue;
            }

            $message = sprintf(
                'The method %s contains a "%s" statement which violates the rule "No logic in Facade"',
                $method->getFullQualifiedName(),
                $statement->getImage()
            );

            $this->addViolation($method, [$message]);
        }
    }

}

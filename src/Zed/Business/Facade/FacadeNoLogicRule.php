<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class FacadeNoLogicRule extends AbstractFacadeRule implements ClassAware
{

    const RULE = 'A Facade must not contain logic and only delegate.';

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::RULE;
    }

    /**
     * @var array
     */
    private $forbiddenStatements = [
        'foreach',
        'while',
        'for',
        'do',
        'if',
    ];

    /**
     * @param \PHPMD\AbstractNode $node
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
     * @param \PHPMD\AbstractNode|\PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    protected function applyRule(ClassNode $node)
    {
        foreach ($node->getMethods() as $method) {
            $this->checkStatements($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function checkStatements(MethodNode $method)
    {
        foreach ($method->findChildrenOfType('Statement') as $statement) {
            if (!in_array(strtolower($statement->getImage()), $this->forbiddenStatements)) {
                continue;
            }

            $message = sprintf(
                'The method %s contains a "%s" statement which violates the rule "' . static::RULE . '"',
                $method->getFullQualifiedName(),
                $statement->getImage()
            );

            $this->addViolation($method, [$message]);
        }
    }

}
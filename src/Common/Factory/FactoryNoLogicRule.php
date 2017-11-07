<?php

namespace ArchitectureSniffer\Common\Factory;

use PDepend\Source\AST\ASTSwitchStatement;
use PHPMD\AbstractNode;
use PHPMD\Node\ASTNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * Factory should not contain business logic, like switch-case and if-else statements.
 */
class FactoryNoLogicRule extends AbstractFactoryRule implements MethodAware
{
    const RULE = 'Factory should not contain any business logic.';
    const SWITCH_STATEMENT = 'switch';

    /**
     * @var array
     */
    protected $forbiddenLogicStatements = [
        'if',
    ];

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
        if (!$this->isFactory($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    protected function applyRule(MethodNode $method)
    {
        foreach ($method->findChildrenOfType('Statement') as $statement) {
            $this->checkForbiddenStatement($method, $statement);
            $this->checkSwitchStatement($method, $statement);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param \PHPMD\AbstractNode|\PHPMD\Node\ASTNode $statement
     *
     * @return void
     */
    protected function checkForbiddenStatement(MethodNode $method, ASTNode $statement)
    {
        if (in_array(strtolower($statement->getImage()), $this->forbiddenLogicStatements)) {
            $this->createViolation($method, $statement->getImage());
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param \PHPMD\AbstractNode|\PHPMD\Node\ASTNode $statement
     *
     * @return void
     */
    protected function checkSwitchStatement(MethodNode $method, ASTNode $statement)
    {
        if ($statement->getNode() instanceof ASTSwitchStatement) {
            $this->createViolation($method, static::SWITCH_STATEMENT);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param string $statementName
     *
     * @return void
     */
    protected function createViolation(MethodNode $method, $statementName)
    {
        $message = sprintf(
            'The method %s contains a "%s" statement which violates the rule "%s"',
            $method->getFullQualifiedName(),
            $statementName,
            static::RULE
        );

        $this->addViolation($method, [$message]);
    }
}

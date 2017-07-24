<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PDepend\Source\AST\ASTParameter;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * Every Facade should only retrieve native types and transfer objects
 */
class ArgumentsFacadeRule extends AbstractFacadeRule implements MethodAware
{

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
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    private function applyRule(MethodNode $method)
    {
        $params = $method->getParameters();
        foreach ($params as $param) {
            $this->checkParameter($param, $method);
        }
    }

    /**
     * @param \PDepend\Source\AST\ASTParameter $param
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    private function checkParameter(ASTParameter $param, AbstractNode $node)
    {
        $class = $param->getClass();
        if (empty($class) || $class->getNamespaceName() === 'Generated\Shared\Transfer') {
            return;
        }

        $message = sprintf(
            'The %s is using an invalid argument type which violates the rule "Should only retrieve native types and transfer objects"',
            $node->getFullQualifiedName()
        );

        $this->addViolation($node, [$message]);
    }

}

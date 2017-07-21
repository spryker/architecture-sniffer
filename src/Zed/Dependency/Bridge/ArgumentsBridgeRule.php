<?php

namespace ArchitectureSniffer\Zed\Dependency\Bridge;

use PDepend\Source\AST\ASTParameter;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * Every bridge should not have type in constructor
 */
class ArgumentsBridgeRule extends AbstractBridgeRule implements MethodAware
{

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (!$this->isBridge($node)) {
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
        if($method->getName() !== '__construct'){
            return;
        }

        $params = $method->getParameters();
        if(count($params) !== 1){
            $message = sprintf(
                'The %s is having too many parameters which violates the rule "Constructor in bridge must have exactly one parameter"',
                $method->getFullQualifiedName()
            );

            $this->addViolation($method, [$message]);
            return;
        }

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

        if (is_null($class)) {
            return;
        }

        $message = sprintf(
            'The %s is violating the rule "Bridges must not have a type-hint in constructor"',
            $node->getFullQualifiedName()
        );

        $this->addViolation($node, [$message]);
    }

}

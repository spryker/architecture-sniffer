<?php

namespace ArchitectureSniffer;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;

/**
 * Class constructor arguments should always use abstractions, programming against an interface instead
 * of a concrete class etc.
 */
class EnforceAbstractionInConstructor extends \PHPMD\AbstractRule
    implements \PHPMD\Rule\ClassAware
{

    /**
     * @inheritdoc
     */
    public function apply(\PHPMD\AbstractNode $node)
    {
        /** @var $node \PHPMD\Node\ClassNode */
        $methods = $node->getMethods();
        foreach ($methods as $method) {
            if (strtolower($method->getName()) !== '__construct') {
                continue;
            }

            $this->check($method, $node);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     * @param \PHPMD\AbstractNode $node
     * @return void
     */
    protected function check(MethodNode $method, AbstractNode $node)
    {
        $params = $method->getParameters();
        foreach ($params as $param) {
            $this->checkParam($param, $node);
        }
    }

    /**
     * @param \PDepend\Source\AST\ASTParameter $param
     * @param \PHPMD\AbstractNode $node
     * @return void
     */
    protected function checkParam(\PDepend\Source\AST\ASTParameter $param, AbstractNode $node) {
        $class = $param->getClass();
        if (empty($class) || $class->isAbstract()) {
            return;
        }

        $this->addViolation($node, [$class->getName()]);
    }

}

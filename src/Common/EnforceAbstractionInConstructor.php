<?php

namespace ArchitectureSniffer\Common;

use PDepend\Source\AST\ASTParameter;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;

/**
 * Class constructor arguments should always use abstractions, programming against an interface instead
 * of a concrete class etc.
 */
class EnforceAbstractionInConstructor extends \PHPMD\AbstractRule implements \PHPMD\Rule\ClassAware
{

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        /** @var \PHPMD\Node\ClassNode $node */
        $methods = $node->getMethods();
        foreach ($methods as $method) {
            if (strtolower($method->getName()) !== '__construct') {
                continue;
            }

            $this->check($method, $node);
        }
    }

    /**
     * @param MethodNode $method
     * @param AbstractNode $node
     *
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
     * @param ASTParameter $param
     * @param AbstractNode $node
     *
     * @return void
     */
    protected function checkParam(ASTParameter $param, AbstractNode $node)
    {
        $class = $param->getClass();
        if (empty($class) || $class->isAbstract()) {
            return;
        }

        $this->addViolation($node, [$class->getName()]);
    }

}

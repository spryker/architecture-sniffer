<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common;

use PDepend\Source\AST\ASTParameter;
use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

/**
 * Class constructor arguments should always use abstractions, programming against an interface instead
 * of a concrete class etc.
 */
class EnforceAbstractionInConstructor extends AbstractRule implements ClassAware
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
     * @param \PHPMD\Node\MethodNode $method
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    protected function check(MethodNode $method, AbstractNode $node)
    {
        $params = $method->getParameters();
        foreach ($params as $param) {
            $this->checkParameter($param, $node);
        }
    }

    /**
     * @param \PDepend\Source\AST\ASTParameter $param
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    protected function checkParameter(ASTParameter $param, AbstractNode $node)
    {
        $class = $param->getClass();
        if (empty($class) || $class->isAbstract()) {
            return;
        }

        $this->addViolation($node, [$class->getName()]);
    }
}

<?php

namespace ArchitectureSniffer\Zed\Dependency\Bridge;

use PDepend\Source\AST\ASTParameter;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * A bridge should not have a typehint in constructor.
 */
class BridgeConstructorArgumentTypehintsRule extends AbstractBridgeRule implements MethodAware
{

    const RULE = 'A bridge must not have a type-hint in constructor.';

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
    protected function applyRule(MethodNode $method)
    {
        if ($method->getName() !== '__construct') {
            return;
        }

        $params = $method->getParameters();
        if (count($params) !== 1) {
            // Let another rule take care of this.
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
    protected function checkParameter(ASTParameter $param, AbstractNode $node)
    {
        $class = $param->getClass();

        if ($class === null) {
            return;
        }

        $message = sprintf(
            'The %s is violating the rule "' . static::RULE . '"',
            $node->getFullQualifiedName()
        );

        $this->addViolation($node, [$message]);
    }

}

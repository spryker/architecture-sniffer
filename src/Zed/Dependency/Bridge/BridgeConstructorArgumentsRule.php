<?php

namespace ArchitectureSniffer\Zed\Dependency\Bridge;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\MethodAware;

/**
 * A bridge should only have a single argument in constructor.
 */
class BridgeConstructorArgumentsRule extends AbstractBridgeRule implements MethodAware
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'A bridge should only have a single argument in constructor. It is also used only on core, not in projects.';
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
        if (count($params) === 1) {
            return;
        }

        $message = sprintf(
            'The %s is having %s parameters which violates the rule "Constructor in bridge must have exactly one parameter"',
            count($params),
            $method->getFullQualifiedName()
        );

        $this->addViolation($method, [$message]);
    }

}

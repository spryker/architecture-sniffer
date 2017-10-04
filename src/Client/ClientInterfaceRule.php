<?php

namespace ArchitectureSniffer\Client;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\InterfaceAware;

class ClientInterfaceRule extends SprykerAbstractRule implements InterfaceAware
{

    const RULE = 'Every method must also contain the @api tag in docblock and a contract text above.';

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
        /** @var \PHPMD\Node\InterfaceNode $node */
        foreach ($node->getMethods() as $method) {
            $this->applyEveryInterfaceMethodMustHaveApiTagAndContractText($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode|\PDepend\Source\AST\ASTMethod $method
     *
     * @return void
     */
    protected function applyEveryInterfaceMethodMustHaveApiTagAndContractText(MethodNode $method)
    {
        $comment = $method->getComment();
        if (preg_match(
            '(
                \*\s+[{}A-Z0-9\-]+.*\s+
                \*?\s*
                \*\s+@api
            )xi',
            $comment
        )) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The client interface method %s does not contain an @api tag or contract text ' .
                    'which violates rule: "Every method must have the @api tag in docblock and a contract text above"',
                    $method->getFullQualifiedName()
                )
            ]
        );
    }

}

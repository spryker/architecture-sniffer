<?php

namespace ArchitectureSniffer\Zed\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

/**
 * Factory methods should not contain loops
 */
class NoLoopsInFactoryRule extends AbstractRule implements ClassAware
{

    /**
     * @var array
     */
    private $forbiddenStatements = [
        'foreach',
        'while',
        'for',
        'do',
    ];

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (!preg_match('/Zed\\.*\\\(Business|Communication|Persistence)\\Factory$/', $node->getName())) {
            return;
        }

        foreach ($node->getMethods() as $method) {
            $this->applyNoLoopsInMethod($method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    private function applyNoLoopsInMethod(MethodNode $method)
    {
        foreach ($method->findChildrenOfType('Statement') as $statement) {
            if (false === in_array(strtolower($statement->getImage()), $this->forbiddenStatements)) {
                continue;
            }

            $message = sprintf(
                'The method %s contains a "%s" statement which violates rule "No loops in factories"',
                $method->getFullQualifiedName(),
                $statement->getImage()
            );

            $this->addViolation($method, [$message]);
        }
    }

}

<?php

namespace ArchitectureSniffer;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class FactoryRule extends AbstractRule implements ClassAware
{
    /**
     * Statements we don't want in factory methods.
     *
     * @var array
     */
    private $forbiddenStatements = [
        'foreach',
        'while',
        'for',
        'do',
    ];

    public function apply(AbstractNode $node)
    {
        if (0 === preg_match('(Factory$)', $node->getName())) {
            return;
        }

        $qName = sprintf('%s\\%s', $node->getNamespaceName(), $node->getName());
        foreach ($node->getMethods() as $method) {
            if (0 !== strpos($method->getName(), 'create')) {
                continue;
            }

            $this->applyNoLoopsInFactories($qName, $method);
        }
    }

    private function applyNoLoopsInFactories($qName, MethodNode $method)
    {

        foreach ($method->findChildrenOfType('Statement') as $statement) {
            if (false === in_array(strtolower($statement->getImage()), $this->forbiddenStatements)) {
                continue;
            }

            $this->addViolation(
                $method,
                [
                    sprintf(
                        'The method %s::%s() contains a "%s" statement which violates rule "No loops in factories"',
                        $qName,
                        $method->getImage(),
                        $statement->getImage()
                    )
                ]
            );
        }

    }
}

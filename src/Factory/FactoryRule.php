<?php

namespace ArchitectureSniffer\Factory;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
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

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (!($node instanceof ClassNode)) {
            return;
        }

        if (0 === preg_match('(Factory$)', $node->getName())) {
            return;
        }

        $this->applyFactoriesAreStateless($node);

        foreach ($node->getMethods() as $method) {
            $this->applyNoLoopsInFactories($method);
            $this->applyCreateMethodContainsOneNew($method);
            $this->applyGetMethodContainsNoNew($method);
            $this->applyOnlyGetOrCreateMethodInFactories($node, $method);
        }
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    private function applyNoLoopsInFactories(MethodNode $method)
    {
        foreach ($method->findChildrenOfType('Statement') as $statement) {
            if (false === in_array(strtolower($statement->getImage()), $this->forbiddenStatements)) {
                continue;
            }

            $this->addViolation(
                $method,
                [
                    sprintf(
                        'The method %s contains a "%s" statement which violates rule "No loops in factories"',
                        $method->getFullQualifiedName(),
                        $statement->getImage()
                    )
                ]
            );
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode $class
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    private function applyOnlyGetOrCreateMethodInFactories(ClassNode $class, MethodNode $method)
    {
        if (0 != preg_match('(^(create|get).+)', $method->getName())) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The factory class %s contains a method %s() which violates rule "Only methods named create*() or get*() in factories"',
                    $class->getFullQualifiedName(),
                    $method->getName()
                )
            ]
        );
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    private function applyCreateMethodContainsOneNew(MethodNode $method)
    {
        if ('create' != substr($method->getName(), 0, 6)) {
            return;
        }

        if (1 === ($count = count($method->findChildrenOfType('AllocationExpression')))) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The factory method %s contains %d new statements which violates rule "A create*() method must contain exactly 1 `new` statement."',
                    $method->getFullQualifiedName(),
                    $count
                )
            ]
        );
    }

    /**
     * @param \PHPMD\Node\MethodNode $method
     *
     * @return void
     */
    private function applyGetMethodContainsNoNew(MethodNode $method)
    {
        if ('get' != substr($method->getName(), 0, 3)) {
            return;
        }

        if (0 === ($count = count($method->findChildrenOfType('AllocationExpression')))) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The factory method %s contains %d new statement%s which violates rule "A get*() method must not contain a `new` keyword."',
                    $method->getFullQualifiedName(),
                    $count,
                    $count === 1 ? '' : 's'
                )
            ]
        );
    }

    /**
     * @param \PHPMD\Node\ClassNode $class
     *
     * @return void
     */
    private function applyFactoriesAreStateless(ClassNode $class)
    {
        if (0 === ($count = count($class->getProperties()))) {
            return;
        }

        $this->addViolation(
            $class,
            [
                sprintf(
                    'The class %s contains %d propert%s which violates rule "Factories are stateless"',
                    $class->getFullQualifiedName(),
                    $count,
                    $count === 1 ? 'y' : 'ies'
                )
            ]
        );
    }

}

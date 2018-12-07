<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common\Factory;

use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

/**
 * Factory methods should not contain loops
 */
class FactoryNoLoopsRule extends AbstractFactoryRule implements ClassAware
{
    public const RULE = 'Factory methods should not contain loops.';

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::RULE;
    }

    /**
     * @var array
     */
    protected $forbiddenStatements = [
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
        if (!$this->isFactoryExceptPersistence($node)) {
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
    protected function applyNoLoopsInMethod(MethodNode $method)
    {
        foreach ($method->findChildrenOfType('Statement') as $statement) {
            if (!in_array(strtolower($statement->getImage()), $this->forbiddenStatements)) {
                continue;
            }

            $message = sprintf(
                'The method %s contains a "%s" statement which violates rule "' . static::RULE . '"',
                $method->getFullQualifiedName(),
                $statement->getImage()
            );

            $this->addViolation($method, [$message]);
        }
    }
}

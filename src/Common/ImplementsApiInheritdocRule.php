<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\ClassAware;

class ImplementsApiInheritdocRule extends SprykerAbstractRule implements ClassAware
{
    public const RULE = 'Every API method must also contain the @inheritdoc tag in docblock and a contract text above.';

    /**
     * @var array
     */
    protected $apiClasses = ['Facade', 'QueryContainer', 'Client', 'Service', 'Plugin'];

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
        $nodeNamespace = $node->getNamespaceName();
        foreach ($this->apiClasses as $apiClass) {
            $nonApiNamespace = 'Dependency\\' . $apiClass;
            if ($this->stringEndsWith($nodeNamespace, $nonApiNamespace)) {
                return;
            }
        }

        $nodeClassName = $node->getName();
        if (!preg_match('/(' . implode('|', $this->apiClasses) . ')$/', $nodeClassName)) {
            return;
        }

        /** @var \PHPMD\Node\InterfaceNode $node */
        foreach ($node->getMethods() as $method) {
            $this->applyEveryInterfaceMethodMustHaveApiTagAndContractText($method);
        }
    }

    /**
     * @param string $string
     * @param string $stringToSearch
     *
     * @return bool
     */
    protected function stringEndsWith($string, $stringToSearch)
    {
        return (substr($string, strlen($string) - strlen($stringToSearch)) === $stringToSearch);
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
                \*\s+@inheritdoc
            )xi',
            $comment
        )) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The interface method %s does not contain an @api tag or contract text ' .
                    'which violates rule: "%s"',
                    $method->getFullQualifiedName(),
                    self::RULE
                ),
            ]
        );
    }
}

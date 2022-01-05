<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\InterfaceAware;

class ApiInterfaceRule extends SprykerAbstractRule implements InterfaceAware
{
    /**
     * @var string
     */
    public const RULE = 'Every method must also contain the @api tag in docblock and a contract text above.';

    /**
     * @var string
     */
    protected $classRegex = '';

    /**
     * @var array
     */
    protected $apiClasses = ['Facade', 'QueryContainer', 'Client', 'Service'];

    /**
     * @return string
     */
    public function getDescription()
    {
        return static::RULE;
    }

    /**
     * @param \PHPMD\Node\InterfaceNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        if (empty($this->classRegex) || preg_match($this->classRegex, $node->getFullQualifiedName()) === 0) {
            return;
        }

        $nodeNamespace = $node->getNamespaceName();
        foreach ($this->apiClasses as $nonApiLayer) {
            $nonApiLayerNamespace = 'Dependency\\' . $nonApiLayer;
            if ($this->stringEndsWith($nodeNamespace, $nonApiLayerNamespace)) {
                return;
            }
        }

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
        if (
            preg_match(
                '(
                \*\s+["{}\[\],=>$A-Z0-9\-]+.*\s+
                \*?\s*
                \*\s+@api
            )xi',
                $comment,
            )
        ) {
            return;
        }

        $this->addViolation(
            $method,
            [
                sprintf(
                    'The interface method `%s()` does not contain an @api tag or contract text ' .
                    'which violates rule "%s"',
                    $method->getFullQualifiedName(),
                    self::RULE,
                ),
            ],
        );
    }
}

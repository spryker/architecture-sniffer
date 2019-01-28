<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Common;

use ArchitectureSniffer\SprykerAbstractRule;
use PHPMD\AbstractNode;
use PHPMD\Node\MethodNode;
use PHPMD\Rule\InterfaceAware;

class ApiInterfaceRule extends SprykerAbstractRule implements InterfaceAware
{
    public const RULE = 'Every method must also contain the @api tag in docblock and a contract text above.';

    /**
     * @var string
     */
    protected $classRegex = '';

    /**
     * @var array
     */
    protected $nonApiLayers = ['Facade', 'QueryContainer', 'Client', 'Service'];

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
        if (empty($this->classRegex) || preg_match($this->classRegex, $node->getFullQualifiedName()) === 0) {
            return;
        }

        $nodeNamespace = $node->getNamespaceName();
        foreach ($this->nonApiLayers as $nonApiLayer) {
            $nonApiLayerNamespace = 'Dependency\\' . $nonApiLayer;
            if ($this->stringEndsWith($nodeNamespace, $nonApiLayerNamespace)) {
                return;
            }
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
                    'The interface method %s does not contain an @api tag or contract text ' .
                    'which violates rule: "%s"',
                    $method->getFullQualifiedName(),
                    self::RULE
                ),
            ]
        );
    }
}

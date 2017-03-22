<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PDepend\Source\AST\ASTArtifactList;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

/**
 * Every Facade must have a Interface named like the Facade + Interface suffix
 */
class FacadeInterfaceRule extends AbstractFacadeRule implements ClassAware
{

    const RULE = 'Must implement an interface with same name and suffix \'Interface\'. Every method must also contain the @api tag in docblock and a contract text above.';

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
        if (!$this->isFacade($node)) {
            return;
        }

        $this->applyRule($node);
    }

    /**
     * @param \PHPMD\AbstractNode|\PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    protected function applyRule(ClassNode $node)
    {
        $implementedInterfaces = $this->getImplementedInterfaces($node);

        if ($implementedInterfaces->count() === 0 || !$this->hasFacadeInterface($node, $implementedInterfaces)) {
            $message = sprintf(
                'The %1$s is missing a "%1$sInterface" which violates the rule "%s"',
                $node->getImage(),
                static::RULE
            );

            $this->addViolation($node, [$message]);
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     *
     * @return \PDepend\Source\AST\ASTArtifactList
     */
    protected function getImplementedInterfaces(ClassNode $node)
    {
        return $node->getInterfaces();
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     * @param \PDepend\Source\AST\ASTArtifactList|\PDepend\Source\AST\ASTInterface[] $implementedInterfaces
     *
     * @return bool
     */
    protected function hasFacadeInterface(ClassNode $node, ASTArtifactList $implementedInterfaces)
    {
        $expectedInterfaceName = $node->getImage() . 'Interface';
        foreach ($implementedInterfaces as $implementedInterface) {
            $interfaceName = $implementedInterface->getName();
            if ($interfaceName === $expectedInterfaceName) {
                return true;
            }
        }

        return false;
    }

}
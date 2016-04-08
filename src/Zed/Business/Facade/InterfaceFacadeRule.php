<?php

namespace ArchitectureSniffer\Zed\Business\Facade;

use PDepend\Source\AST\ASTArtifactList;
use PHPMD\AbstractNode;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;

/**
 * Every Facade must have a Interface named like the Facade + Interface suffix
 */
class InterfaceFacadeRule extends AbstractFacadeRule implements ClassAware
{

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
    private function applyRule(ClassNode $node)
    {
        $implementedInterfaces = $this->getImplementedInterfaces($node);

        if ($implementedInterfaces->count() === 0 || !$this->hasFacadeInterface($node, $implementedInterfaces)) {
            $message = sprintf(
                'The %1$s is missing a "%1$sInterface" which violates the rule "Implements interface with suffix Interface"',
                $node->getImage()
            );

            $this->addViolation($node, [$message]);
        }
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     *
     * @return \PDepend\Source\AST\ASTArtifactList
     */
    private function getImplementedInterfaces(ClassNode $node)
    {
        return $node->getInterfaces();
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     * @param \PDepend\Source\AST\ASTArtifactList|\PDepend\Source\AST\ASTInterface[] $implementedInterfaces
     *
     * @return bool
     */
    private function hasFacadeInterface(ClassNode $node, ASTArtifactList $implementedInterfaces)
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

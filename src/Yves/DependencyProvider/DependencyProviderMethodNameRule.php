<?php

namespace ArchitectureSniffer\Yves\DependencyProvider;

use ArchitectureSniffer\Common\DependencyProvider\AbstractDependencyProviderRule;
use PHPMD\AbstractNode;
use PHPMD\Rule\MethodAware;

class DependencyProviderMethodNameRule extends AbstractDependencyProviderRule implements MethodAware
{

    /**
     * @var array
     */
    protected $allowedProvideMethodNames = [
        'provideDependencies',
    ];

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
        if (!$this->isDependencyProvider($node, 'Yves')) {
            return;
        }

        $this->applyRule($node, $this->allowedProvideMethodNames);
    }

}

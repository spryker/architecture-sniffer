<?php

namespace ArchitectureSniffer\Zed\DependencyProvider;

use ArchitectureSniffer\Common\DependencyProvider\AbstractDependencyProviderRule;
use PHPMD\AbstractNode;
use PHPMD\Rule\MethodAware;

class DependencyProviderMethodNameRule extends AbstractDependencyProviderRule implements MethodAware
{

    /**
     * @var array
     */
    protected $allowedProvideMethodNames = [
        'provideCommunicationLayerDependencies',
        'provideBusinessLayerDependencies',
        'providePersistenceLayerDependencies',
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
        if (!$this->isDependencyProvider($node, 'Zed')) {
            return;
        }

        $this->applyRule($node, $this->allowedProvideMethodNames);
    }

}

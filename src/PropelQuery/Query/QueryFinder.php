<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Query;

use PHPMD\Node\AbstractNode;
use Roave\BetterReflection\Reflection\ReflectionClass;

class QueryFinder implements QueryFinderInterface
{
    protected const PATTERN_QUERY_METHOD_NAME = '/^(create|get).*Query?/';

    /**
     * @param \PHPMD\Node\AbstractNode $node
     * @param \Roave\BetterReflection\Reflection\ReflectionClass $reflectionNodeClass
     *
     * @return string[]
     */
    public function getQueryNames(AbstractNode $node, ReflectionClass $reflectionNodeClass): array
    {
        $queryNames = [];

        $queryNodes = $this->getQueryNodes($node);

        foreach ($queryNodes as $queryNode) {
            $queryName = $queryNode->getName();

            if (!$this->isQueryMethod($queryName) || $reflectionNodeClass->hasMethod($queryName)) {
                continue;
            }

            $queryNames[] = $queryName;
        }

        return array_unique($queryNames);
    }

    /**
     * @param string $queryName
     *
     * @return bool
     */
    protected function isQueryMethod(string $queryName): bool
    {
        return (bool)preg_match(static::PATTERN_QUERY_METHOD_NAME, $queryName);
    }

    /**
     * @param \PHPMD\Node\AbstractNode $node
     *
     * @return \PHPMD\Node\AbstractNode[]
     */
    protected function getQueryNodes(AbstractNode $node): array
    {
        $methodPostfix = $node->findChildrenOfType('MethodPostfix');
        $classOrInterfaceReference = $node->findChildrenOfType('ClassOrInterfaceReference');

        return array_merge($methodPostfix, $classOrInterfaceReference);
    }
}

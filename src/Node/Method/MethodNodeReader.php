<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\Method;

use PHPMD\AbstractNode;

class MethodNodeReader implements MethodNodeReaderInterface
{
    protected const PATTERN_JOIN_METHOD_NAME = '/^(left|inner|right|add)?(Join|join)(With|Condition)?/';
    protected const PATTERN_QUERY_METHOD_NAME = '/^(create|get)|Query?/';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string[]
     */
    public function getRelationNames(AbstractNode $node): array
    {
        $relationNames = [];

        $children = $node->findChildrenOfType('MethodPostfix');

        foreach ($children as $child) {
            if (stripos($child->getName(), 'join') === false) {
                continue;
            }

            $relationNames[] = $this->getRelationNameFormJoinNode($child);
        }

        return $relationNames;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getModuleName(AbstractNode $node): string
    {
        $filePath = $node->getFileName();
        $filePath = preg_replace('/^.+Zed\//', '', $filePath);

        return preg_replace('/\/Persistence.+$/', '', $filePath);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getQueryModuleName(AbstractNode $node): string
    {
        $methodPostfix = $node->findChildrenOfType('MethodPostfix');
        $classOrInterfaceReference = $node->findChildrenOfType('ClassOrInterfaceReference');

        /**@var AbstractNode[] $children*/
        $children = array_merge($methodPostfix, $classOrInterfaceReference);

        foreach ($children as $child) {
            if (stripos($child->getName(), 'Query') === false) {
                continue;
            }

            return preg_replace(
                static::PATTERN_QUERY_METHOD_NAME,
                '',
                $child->getName()
            );
        }

        dd($node->getFileName()); //todo: delete
        return null;
    }

    /**
     * @param \PHPMD\AbstractNode $join
     *
     * @return string
     */
    protected function getRelationNameFormJoinNode(AbstractNode $join): string
    {
        $relationName = $this->getRelationNameFromMethodName($join);

        if ($relationName !== '') {
            return $relationName;
        }

        return $this->getRelationNameFromJoinParams($join);
    }

    /**
     * @param \PHPMD\AbstractNode $child
     *
     * @return string
     */
    protected function getRelationNameFromJoinParams(AbstractNode $child): string
    {
        $relationArgument = $child->getFirstChildOfType('Arguments');
        $relationArgument = $relationArgument->getFirstChildOfType('Literal');

        return trim($relationArgument->getName(), '\'');
    }

    /**
     * @param \PHPMD\AbstractNode $join
     *
     * @return string
     */
    protected function getRelationNameFromMethodName(AbstractNode $join): string
    {
        $joinRelationName = preg_replace(
            static::PATTERN_JOIN_METHOD_NAME,
            '',
            $join->getName()
        );

        return $joinRelationName;
    }
}

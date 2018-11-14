<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\Method;

use PHPMD\AbstractNode;

class NodeMethodReader implements NodeMethodReaderInterface
{
    protected const PATTERN_JOIN_METHOD_NAME = '/^(left|inner|right|add)?(Join|join)(With|Condition)?/';

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string[]
     */
    public function getJoinNames(AbstractNode $node): array
    {
        $joins = [];

        $children = $node->findChildrenOfType('MethodPostfix');

        foreach ($children as $child) {
            if (stripos($child->getName(), 'join') === false) {
                continue;
            }

            $joins[] = $this->getJoinRelationName($child);
        }

        return $joins;
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
        $queryName = $node->getFirstChildOfType('ClassOrInterfaceReference')->getName();
        $queryName = preg_replace('/^.+(Zed\\\\)/', '', $queryName);

        return preg_replace('/\\\\.+$/', '', $queryName);
    }

    /**
     * @param \PHPMD\AbstractNode $join
     *
     * @return string
     */
    protected function getJoinRelationName(AbstractNode $join): string
    {
        $joinName = $this->getJoinRelationNameFromMethodName($join);

        if ($joinName !== '') {
            return $joinName;
        }

        return $this->getJoinRelationNameFromParams($join);
    }

    /**
     * @param \PHPMD\AbstractNode $child
     *
     * @return string
     */
    protected function getJoinRelationNameFromParams(AbstractNode $child): string
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
    protected function getJoinRelationNameFromMethodName(AbstractNode $join): string
    {
        $splitMethodNameByPattern = preg_split(
            static::PATTERN_JOIN_METHOD_NAME,
            $join->getName(),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        if ($splitMethodNameByPattern === []) {
            return '';
        }

        return $splitMethodNameByPattern[0];
    }
}

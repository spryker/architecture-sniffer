<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\PropelQuery\Relation;

use PHPMD\AbstractNode;
use Roave\BetterReflection\Reflection\ReflectionClass;

class RelationFinder implements RelationFinderInterface
{
    protected const PATTERN_JOIN_METHOD_NAME = '/^(left|inner|right|add)?(Join|join)(With)?/';
    protected const PATTERN_IGNORE_JOIN_NAME = '/(Condition|Object)/';

    /**
     * @param \PHPMD\AbstractNode $node
     * @param \Roave\BetterReflection\Reflection\ReflectionClass $reflectionNodeClass
     *
     * @return string[]
     */
    public function getRelationNames(AbstractNode $node, ReflectionClass $reflectionNodeClass): array
    {
        $relationNames = [];

        $children = $node->findChildrenOfType('MethodPostfix');

        foreach ($children as $child) {
            $relationMethodName = $child->getName();

            if (stripos($relationMethodName, 'join') === false) {
                continue;
            }

            if ($reflectionNodeClass->hasMethod($relationMethodName)) {
                continue;
            }

            $relationName = $this->getRelationNameFormJoinNode($child, $reflectionNodeClass);

            if (!strpos($relationName, '.')) {
                $relationNames[] = $relationName;

                continue;
            }

            $embeddedRelationNames = explode('.', $relationName);

            foreach ($embeddedRelationNames as $relationName) {
                $relationNames[] = $relationName;
            }
        }

        return array_unique(array_filter($relationNames));
    }

    /**
     * @param \PHPMD\AbstractNode $relationNode
     * @param \Roave\BetterReflection\Reflection\ReflectionClass $reflectionNodeClass
     *
     * @return string|null
     */
    protected function getRelationNameFormJoinNode(AbstractNode $relationNode, ReflectionClass $reflectionNodeClass): ?string
    {
        $relationName = $this->getRelationNameFromMethodName($relationNode);

        if ($relationName !== '') {
            return $relationName;
        }

        return $this->getRelationNameFromJoinParams($relationNode, $reflectionNodeClass);
    }

    /**
     * @param \PHPMD\AbstractNode $joinNode
     *
     * @return string|null
     */
    protected function getRelationNameFromMethodName(AbstractNode $joinNode): ?string
    {
        $joinRelationName = preg_replace(
            static::PATTERN_JOIN_METHOD_NAME,
            '',
            $joinNode->getName()
        );

        if (preg_match(static::PATTERN_IGNORE_JOIN_NAME, $joinRelationName)) {
            return null;
        }

        return $joinRelationName;
    }

    /**
     * @param \PHPMD\AbstractNode $joinNode
     * @param \Roave\BetterReflection\Reflection\ReflectionClass $reflectionNodeClass
     *
     * @return string|null
     */
    protected function getRelationNameFromJoinParams(AbstractNode $joinNode, ReflectionClass $reflectionNodeClass): ?string
    {
        //todo: refactoring
        $relationArgument = $joinNode->getFirstChildOfType('Arguments');
        $stringRelationArgument = $relationArgument->getFirstChildOfType('Literal');

        if ($stringRelationArgument !== null) {
            $relationArgumentName = trim($stringRelationArgument->getName(), '\'');

            if (!ctype_alpha($relationArgumentName)) {
                return null;
            }

            return $relationArgumentName;
        }

        $constRelationArgument = $relationArgument->getFirstChildOfType('ConstantPostfix');

        if ($constRelationArgument !== null) {
            return $reflectionNodeClass->getConstant($constRelationArgument->getName());
        }

        return null;
    }
}

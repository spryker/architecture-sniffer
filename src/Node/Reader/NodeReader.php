<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Node\Reader;

use PHPMD\AbstractNode;
use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\BetterReflection\Reflector\ClassReflector;

class NodeReader implements NodeReaderInterface
{
    /**
     * @var \PHPStan\BetterReflection\Reflector\ClassReflector
     */
    protected $classReflector;

    /**
     * @param \PHPStan\BetterReflection\Reflector\ClassReflector $classReflector
     */
    public function __construct(ClassReflector $classReflector)
    {
        $this->classReflector = $classReflector;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getModuleName(AbstractNode $node): string
    {
        $filePath = $node->getFileName();

        $moduleNamePattern = '/^.+(Spryker([a-zA-Z]+|)|Pyz)\%1$s[a-zA-Z]+\%1$s/';
        $moduleNamePattern = sprintf($moduleNamePattern, DIRECTORY_SEPARATOR);

        $moduleName = preg_replace($moduleNamePattern, '', $filePath);
        $moduleName = explode(DIRECTORY_SEPARATOR, $moduleName);

        return array_shift($moduleName);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return string
     */
    public function getClassName(AbstractNode $node): string
    {
        $fullQualifiedName = $node->getFullQualifiedName();
        $fullQualifiedName = explode('::', $fullQualifiedName);

        return array_shift($fullQualifiedName);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return \PHPStan\BetterReflection\Reflection\ReflectionClass
     */
    public function getReflectionClassByNode(AbstractNode $node): ReflectionClass
    {
        $nodeClassName = $this->getClassName($node); //todo: check for class

        return $this->classReflector->reflect($nodeClassName);
    }
}

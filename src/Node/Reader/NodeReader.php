<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\Node\Reader;

use PHPMD\AbstractNode;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;

class NodeReader implements NodeReaderInterface
{
    /**
     * @var \Roave\BetterReflection\Reflector\ClassReflector
     */
    protected $classReflector;

    /**
     * @param \Roave\BetterReflection\Reflector\ClassReflector $classReflector
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
        $filePath = preg_replace('/^.+Zed\//', '', $filePath);

        return preg_replace('/\/Persistence.+$/', '', $filePath);
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
     * @return \Roave\BetterReflection\Reflection\ReflectionClass
     */
    public function getReflectionClassByNode(AbstractNode $node): ReflectionClass
    {
        $nodeClassName = $this->getClassName($node); //todo: check for class

        return $this->classReflector->reflect($nodeClassName);
    }
}
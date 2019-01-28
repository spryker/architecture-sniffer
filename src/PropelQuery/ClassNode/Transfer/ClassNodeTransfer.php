<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\ClassNode\Transfer;

use ArchitectureSniffer\Path\Transfer\PathTransfer;
use ArchitectureSniffer\Transfer\TransferInterface;
use PHPMD\Node\ClassNode;
use Roave\BetterReflection\Reflection\ReflectionClass;

class ClassNodeTransfer implements TransferInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $classModuleName;

    /**
     * @var string
     */
    protected $classFilePath;

    /**
     * @var string
     */
    protected $classNamespace;

    /**
     * @var \PHPMD\Node\ClassNode
     */
    protected $node;

    /**
     * @var \Roave\BetterReflection\Reflection\ReflectionClass
     */
    protected $reflectionClass;

    /**
     * @var \ArchitectureSniffer\Path\Transfer\PathTransfer
     */
    protected $pathTransfer;

    /**
     * @return \PHPMD\Node\ClassNode
     */
    public function getNode(): ClassNode
    {
        return $this->node;
    }

    /**
     * @param \PHPMD\Node\ClassNode $node
     *
     * @return void
     */
    public function setNode(ClassNode $node): void
    {
        $this->node = $node;
    }

    /**
     * @return \Roave\BetterReflection\Reflection\ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass
    {
        return $this->reflectionClass;
    }

    /**
     * @param \Roave\BetterReflection\Reflection\ReflectionClass $reflectionClass
     *
     * @return void
     */
    public function setReflectionClass(ReflectionClass $reflectionClass): void
    {
        $this->reflectionClass = $reflectionClass;
    }

    /**
     * @return \ArchitectureSniffer\Path\Transfer\PathTransfer
     */
    public function getPathTransfer(): PathTransfer
    {
        return $this->pathTransfer;
    }

    /**
     * @param \ArchitectureSniffer\Path\Transfer\PathTransfer $pathTransfer
     *
     * @return void
     */
    public function setPathTransfer(PathTransfer $pathTransfer): void
    {
        $this->pathTransfer = $pathTransfer;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
     * @return void
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassModuleName(): string
    {
        return $this->classModuleName;
    }

    /**
     * @param string $classModuleName
     *
     * @return void
     */
    public function setClassModuleName(string $classModuleName): void
    {
        $this->classModuleName = $classModuleName;
    }

    /**
     * @return string
     */
    public function getClassFilePath(): string
    {
        return $this->classFilePath;
    }

    /**
     * @param string $classFilePath
     *
     * @return void
     */
    public function setClassFilePath(string $classFilePath): void
    {
        $this->classFilePath = $classFilePath;
    }

    /**
     * @return string
     */
    public function getClassNamespace(): string
    {
        return $this->classNamespace;
    }

    /**
     * @param string $classNamespace
     *
     * @return void
     */
    public function setClassNamespace(string $classNamespace): void
    {
        $this->classNamespace = $classNamespace;
    }
}

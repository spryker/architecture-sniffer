<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ArchitectureSniffer\PropelQuery\Method\Transfer;

use ArchitectureSniffer\Transfer\TransferInterface;

class MethodTransfer implements TransferInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $nameSpace;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var string[]
     */
    protected $queryNames = [];

    /**
     * @var string[]
     */
    protected $relationNames = [];

    /**
     * @var string[]
     */
    protected $declaredDependentModuleNames = [];

    /**
     * @return string|null
     */
    public function getMethodName(): ?string
    {
        return $this->methodName;
    }

    /**
     * @param string $methodName
     *
     * @return void
     */
    public function setMethodName(string $methodName): void
    {
        $this->methodName = $methodName;
    }

    /**
     * @return string[]
     */
    public function getQueryNames(): array
    {
        return $this->queryNames;
    }

    /**
     * @param string[] $queryNames
     *
     * @return void
     */
    public function setQueryNames(array $queryNames): void
    {
        $this->queryNames = $queryNames;
    }

    /**
     * @return string[]
     */
    public function getRelationNames(): array
    {
        return $this->relationNames;
    }

    /**
     * @param string[] $relationNames
     *
     * @return void
     */
    public function setRelationNames(array $relationNames): void
    {
        $this->relationNames = $relationNames;
    }

    /**
     * @return string[]
     */
    public function getDeclaredDependentModuleNames(): array
    {
        return $this->declaredDependentModuleNames;
    }

    /**
     * @param string[] $declaredDependentModuleNames
     *
     * @return void
     */
    public function setDeclaredDependentModuleNames(array $declaredDependentModuleNames): void
    {
        $this->declaredDependentModuleNames = $declaredDependentModuleNames;
    }

    /**
     * @return string|null
     */
    public function getClassName(): ?string
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
     * @return string|null
     */
    public function getNameSpace(): ?string
    {
        return $this->nameSpace;
    }

    /**
     * @param string $nameSpace
     *
     * @return void
     */
    public function setNamespace(string $nameSpace): void
    {
        $this->nameSpace = $nameSpace;
    }
}

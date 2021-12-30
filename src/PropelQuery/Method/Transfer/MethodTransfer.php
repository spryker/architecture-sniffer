<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @var array<string>
     */
    protected $queryNames = [];

    /**
     * @var array<string>
     */
    protected $relationNames = [];

    /**
     * @var array<string>
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
     * @return array<string>
     */
    public function getQueryNames(): array
    {
        return $this->queryNames;
    }

    /**
     * @param array<string> $queryNames
     *
     * @return void
     */
    public function setQueryNames(array $queryNames): void
    {
        $this->queryNames = $queryNames;
    }

    /**
     * @return array<string>
     */
    public function getRelationNames(): array
    {
        return $this->relationNames;
    }

    /**
     * @param array<string> $relationNames
     *
     * @return void
     */
    public function setRelationNames(array $relationNames): void
    {
        $this->relationNames = $relationNames;
    }

    /**
     * @return array<string>
     */
    public function getDeclaredDependentModuleNames(): array
    {
        return $this->declaredDependentModuleNames;
    }

    /**
     * @param array<string> $declaredDependentModuleNames
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

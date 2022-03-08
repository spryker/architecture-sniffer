<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSniffer\Common\Method;

use ArchitectureSniffer\Common\DeprecationTrait;
use ArchitectureSniffer\Common\PhpDocTrait;
use ArchitectureSniffer\Common\PhpTypesTrait;
use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\ClassNode;
use PHPMD\Rule\ClassAware;
use ReflectionException;
use ReflectionMethod;

class ExternalMethodExtensionReturnTypeRule extends AbstractRule implements ClassAware
{
    use DeprecationTrait;
    use PhpDocTrait;
    use PhpTypesTrait;

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node): void
    {
        if (
            !$node instanceof ClassNode
            || $this->isClassDeprecated($node)
            || !class_exists($node->getFullQualifiedName())
        ) {
            return;
        }

        foreach ($node->getMethods() as $methodNode) {
            if ($this->isMethodDeprecated($methodNode)) {
                continue;
            }

            $className = $node->getFullQualifiedName();

            $reflectionMethod = new ReflectionMethod(
                $className,
                $methodNode->getName(),
            );

            if ($reflectionMethod->hasReturnType()) {
                continue;
            }

            $initialMethod = $this->getInitialMethod($reflectionMethod);

            if (
                $initialMethod === null
                || $initialMethod->getDeclaringClass() === $className
                || $this->isInternalClass($initialMethod->getDeclaringClass()->getName())
            ) {
                continue;
            }

            $returnType = $this->getReturnTypeByPhpDoc($initialMethod->getDocComment());

            if (
                $returnType == null
                || $this->isTypeInPhp7NotAllowed($returnType)
            ) {
                continue;
            }

            $message = sprintf(
                'Method `%s` must have return type.',
                $methodNode->getFullQualifiedName(),
            );

            $this->addViolation($methodNode, [$message]);
        }
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function isInternalClass(string $className): bool
    {
        return strpos($className, 'Spryker') === 0;
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     * @param \ReflectionMethod|null $initialReflectionMethod
     *
     * @return \ReflectionMethod|null
     */
    protected function getInitialMethod(ReflectionMethod $reflectionMethod, ?ReflectionMethod $initialReflectionMethod = null): ?ReflectionMethod
    {
        try {
            $prototypeMethod = $reflectionMethod->getPrototype();
        } catch (ReflectionException $exception) {
            return $initialReflectionMethod ?? null;
        }

        return $this->getInitialMethod($prototypeMethod, $prototypeMethod);
    }
}

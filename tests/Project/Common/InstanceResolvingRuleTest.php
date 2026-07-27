<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Project\Common;

use ArchitectureSniffer\Project\Common\InstanceResolvingRule;
use ArchitectureSnifferTest\AbstractArchitectureSnifferRuleTest;
use ReflectionClass;

class InstanceResolvingRuleTest extends AbstractArchitectureSnifferRuleTest
{
    /**
     * @dataProvider resolvableFullyQualifiedClassNameProvider
     */
    public function testInstancePatternsMatchResolvableClassNames(string $fullyQualifiedClassName): void
    {
        $this->assertTrue(
            $this->matchesAnyInstancePattern($fullyQualifiedClassName),
            sprintf('Expected "%s" to match at least one INSTANCE_PATTERNS entry.', $fullyQualifiedClassName),
        );
    }

    /**
     * @dataProvider nonResolvableFullyQualifiedClassNameProvider
     */
    public function testInstancePatternsDoNotMatchNonResolvableClassNames(string $fullyQualifiedClassName): void
    {
        $this->assertFalse(
            $this->matchesAnyInstancePattern($fullyQualifiedClassName),
            sprintf('Expected "%s" to match no INSTANCE_PATTERNS entry.', $fullyQualifiedClassName),
        );
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function resolvableFullyQualifiedClassNameProvider(): array
    {
        return [
            'zed persistence repository' => ['Pyz\Zed\Foo\Persistence\FooRepository'],
            'zed persistence entity manager' => ['Pyz\Zed\Foo\Persistence\FooEntityManager'],
            'zed business facade' => ['Pyz\Zed\Foo\Business\FooFacade'],
            'zed business factory' => ['Pyz\Zed\Foo\Business\FooBusinessFactory'],
            'zed communication factory' => ['Pyz\Zed\Foo\Communication\FooCommunicationFactory'],
            'zed dependency provider' => ['Pyz\Zed\Foo\FooDependencyProvider'],
            'client dependency provider' => ['Pyz\Client\Foo\FooDependencyProvider'],
            'service service' => ['Pyz\Service\Foo\FooService'],
            'glue factory' => ['Pyz\Glue\Foo\FooFactory'],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function nonResolvableFullyQualifiedClassNameProvider(): array
    {
        return [
            'plain transfer' => ['Generated\Shared\Transfer\FooTransfer'],
            'zed model' => ['Pyz\Zed\Foo\Business\Model\FooReader'],
            'yves controller' => ['Pyz\Yves\Foo\Controller\IndexController'],
        ];
    }

    protected function matchesAnyInstancePattern(string $fullyQualifiedClassName): bool
    {
        $patterns = (new ReflectionClass(InstanceResolvingRule::class))->getConstant('INSTANCE_PATTERNS');

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $fullyQualifiedClassName) === 1) {
                return true;
            }
        }

        return false;
    }

    public function testRuleAppliesWhenResolvableInstanceIsInitializedWithNew(): void
    {
        $rule = new InstanceResolvingRule();
        $rule->setReport($this->getReportMock(1));
        $rule->apply($this->getClassNode());
    }

    public function testRuleDoesNotApplyWhenNoResolvableInstanceIsInitialized(): void
    {
        $rule = new InstanceResolvingRule();
        $rule->setReport($this->getReportMock(0));
        $rule->apply($this->getClassNode());
    }

    public function testGetDescription(): void
    {
        $this->assertIsString((new InstanceResolvingRule())->getDescription());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ArchitectureSnifferTest\Common\Path;

use ArchitectureSniffer\Path\PathBuilder;
use Codeception\Test\Unit;

class PathBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const FIXTURE_PATH = __DIR__ . '/../../_data/PathBuilderLayouts';

    /**
     * @return void
     */
    public function testGetCorePathResolvesOrgDirectoryForInTreeLayout(): void
    {
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('in-tree/src/Spryker/Customer/src/Spryker/Zed/Customer/Persistence/CustomerRepository.php');

        $corePath = $pathBuilder->getCorePath($filePath);

        $this->assertSame($this->getFixturePath('in-tree/src/Spryker') . DIRECTORY_SEPARATOR, $corePath);
    }

    /**
     * @return void
     */
    public function testGetCorePathResolvesVendorDirectoryForVendorLayout(): void
    {
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('vendor-layout/vendor/spryker/comment/src/Spryker/Zed/Comment/Persistence/CommentRepository.php');

        $corePath = $pathBuilder->getCorePath($filePath);

        $this->assertSame($this->getFixturePath('vendor-layout/vendor/spryker') . DIRECTORY_SEPARATOR, $corePath);
    }

    /**
     * @return void
     */
    public function testGetCoreModulePathPicksPascalCaseModuleDirectoryForInTreeLayout(): void
    {
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('in-tree/src/Spryker/Customer/src/Spryker/Zed/Customer/Persistence/CustomerRepository.php');
        $pathTransfer = $pathBuilder->getPath($filePath);

        $coreModulePath = $pathBuilder->getCoreModulePathByModuleName('Customer', $pathTransfer);

        $this->assertSame(
            realpath($this->getFixturePath('in-tree/src/Spryker/Customer/src/Spryker/Zed/Customer')),
            realpath($coreModulePath),
        );
    }

    /**
     * @return void
     */
    public function testGetCoreModulePathPicksSnakeCaseModuleDirectoryForVendorLayout(): void
    {
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('vendor-layout/vendor/spryker/comment/src/Spryker/Zed/Comment/Persistence/CommentRepository.php');
        $pathTransfer = $pathBuilder->getPath($filePath);

        $coreModulePath = $pathBuilder->getCoreModulePathByModuleName('Customer', $pathTransfer);

        $this->assertSame(
            realpath($this->getFixturePath('vendor-layout/vendor/spryker/customer/src/Spryker/Zed/Customer')),
            realpath($coreModulePath),
        );
    }

    /**
     * @return void
     */
    public function testGetCoreModulePathPicksPascalCaseModuleDirectoryForBundlesLayout(): void
    {
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('bundles/Bundles/Comment/src/Spryker/Zed/Comment/Persistence/CommentRepository.php');
        $pathTransfer = $pathBuilder->getPath($filePath);

        $coreModulePath = $pathBuilder->getCoreModulePathByModuleName('Customer', $pathTransfer);

        $this->assertSame(
            realpath($this->getFixturePath('bundles/Bundles/Customer/src/Spryker/Zed/Customer')),
            realpath($coreModulePath),
        );
    }

    /**
     * @return void
     */
    public function testGetProjectModulePathPicksClassicLayoutWhenItExists(): void
    {
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('project-classic/src/Pyz/Zed/Comment/Persistence/CommentRepository.php');
        $pathTransfer = $pathBuilder->getPath($filePath);

        $projectModulePath = $pathBuilder->getProjectModulePathByModuleName('Customer', $pathTransfer);

        $this->assertSame(
            realpath($this->getFixturePath('project-classic/src/Pyz/Zed/Customer')),
            realpath($projectModulePath),
        );
    }

    /**
     * @return void
     */
    public function testGetProjectModulePathFallsBackToSplitLayout(): void
    {
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('project-split/src/Pyz/Comment/src/Pyz/Zed/Comment/Persistence/CommentRepository.php');
        $pathTransfer = $pathBuilder->getPath($filePath);

        $projectModulePath = $pathBuilder->getProjectModulePathByModuleName('Customer', $pathTransfer);

        $this->assertSame(
            realpath($this->getFixturePath('project-split/src/Pyz/Customer/src/Pyz/Zed/Customer')),
            realpath($projectModulePath),
        );
    }

    /**
     * @param string $relativePath
     *
     * @return string
     */
    protected function getFixturePath(string $relativePath): string
    {
        return static::FIXTURE_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    }
}

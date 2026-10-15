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
    protected const string FIXTURE_PATH = __DIR__ . '/../../_data/PathBuilderLayouts';

    public function testGivenInTreeLayoutFilePathWhenGetCorePathIsCalledThenOrgDirectoryIsReturned(): void
    {
        // Arrange
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('in-tree/src/Spryker/Customer/src/Spryker/Zed/Customer/Persistence/CustomerRepository.php');

        // Act
        $corePath = $pathBuilder->getCorePath($filePath);

        // Assert
        $this->assertSame($this->getFixturePath('in-tree/src/Spryker') . DIRECTORY_SEPARATOR, $corePath);
    }

    public function testGivenVendorLayoutFilePathWhenGetCorePathIsCalledThenVendorSprykerDirectoryIsReturned(): void
    {
        // Arrange
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('vendor-layout/vendor/spryker/comment/src/Spryker/Zed/Comment/Persistence/CommentRepository.php');

        // Act
        $corePath = $pathBuilder->getCorePath($filePath);

        // Assert
        $this->assertSame($this->getFixturePath('vendor-layout/vendor/spryker') . DIRECTORY_SEPARATOR, $corePath);
    }

    public function testGivenInTreeLayoutWhenGetCoreModulePathIsCalledThenPascalCaseModuleDirectoryIsReturned(): void
    {
        // Arrange
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('in-tree/src/Spryker/Customer/src/Spryker/Zed/Customer/Persistence/CustomerRepository.php');
        $pathTransfer = $pathBuilder->getPath($filePath);

        // Act
        $coreModulePath = $pathBuilder->getCoreModulePathByModuleName('Customer', $pathTransfer);

        // Assert
        $this->assertSame(
            realpath($this->getFixturePath('in-tree/src/Spryker/Customer/src/Spryker/Zed/Customer')),
            realpath($coreModulePath),
        );
    }

    public function testGivenVendorLayoutWhenGetCoreModulePathIsCalledThenSnakeCaseModuleDirectoryIsReturned(): void
    {
        // Arrange
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('vendor-layout/vendor/spryker/comment/src/Spryker/Zed/Comment/Persistence/CommentRepository.php');
        $pathTransfer = $pathBuilder->getPath($filePath);

        // Act
        $coreModulePath = $pathBuilder->getCoreModulePathByModuleName('Customer', $pathTransfer);

        // Assert
        $this->assertSame(
            realpath($this->getFixturePath('vendor-layout/vendor/spryker/customer/src/Spryker/Zed/Customer')),
            realpath($coreModulePath),
        );
    }

    public function testGivenClassicProjectLayoutWhenGetProjectModulePathIsCalledThenClassicModuleDirectoryIsReturned(): void
    {
        // Arrange
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('project-classic/src/Pyz/Zed/Comment/Persistence/CommentRepository.php');
        $pathTransfer = $pathBuilder->getPath($filePath);

        // Act
        $projectModulePath = $pathBuilder->getProjectModulePathByModuleName('Customer', $pathTransfer);

        // Assert
        $this->assertSame(
            realpath($this->getFixturePath('project-classic/src/Pyz/Zed/Customer')),
            realpath($projectModulePath),
        );
    }

    public function testGivenSplitProjectLayoutWhenGetProjectModulePathIsCalledThenSplitModuleDirectoryIsReturned(): void
    {
        // Arrange
        $pathBuilder = new PathBuilder();
        $filePath = $this->getFixturePath('project-split/src/Pyz/Comment/src/Pyz/Zed/Comment/Persistence/CommentRepository.php');
        $pathTransfer = $pathBuilder->getPath($filePath);

        // Act
        $projectModulePath = $pathBuilder->getProjectModulePathByModuleName('Customer', $pathTransfer);

        // Assert
        $this->assertSame(
            realpath($this->getFixturePath('project-split/src/Pyz/Customer/src/Pyz/Zed/Customer')),
            realpath($projectModulePath),
        );
    }

    protected function getFixturePath(string $relativePath): string
    {
        return static::FIXTURE_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    }
}

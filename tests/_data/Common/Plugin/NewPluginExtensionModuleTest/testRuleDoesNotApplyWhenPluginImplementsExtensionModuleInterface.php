<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Module\Communication\Plugin\NewPluginName;

use Spryker\Zed\OtherModuleExtension\Dependency\Plugin\ExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

class NewPlugin extends AbstractPlugin implements ExpanderPluginInterface
{
    public function buildForm(FormBuilderInterface $builder): void
    {
    }
}

abstract class AbstractPlugin
{
}

namespace Spryker\Zed\OtherModuleExtension\Dependency\Plugin;

interface ExpanderPluginInterface
{
}

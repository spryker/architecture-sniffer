<?php

namespace Spryker\Zed\Module\Communication\Plugin\OldPluginName;

use Spryker\Zed\OtherModule\Dependency\Plugin\ExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

class OldPlugin extends AbstractPlugin implements ExpanderPluginInterface
{
    public function buildForm(FormBuilderInterface $builder): void
    {
    }
}

abstract class AbstractPlugin
{
}

namespace Spryker\Zed\OtherModule\Dependency\Plugin;

interface ExpanderPluginInterface
{
}
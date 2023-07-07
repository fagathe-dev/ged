<?php

namespace Tracking;

use Tracking\TrackingBundleCompilerPass;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TrackingBundle extends AbstractBundle
{

    /**
     * @param  mixed $container
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TrackingBundleCompilerPass());
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return __DIR__;
    }
}
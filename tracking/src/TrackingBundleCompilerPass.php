<?php

namespace Tracking;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TrackingBundleCompilerPass implements CompilerPassInterface
{    
    /**
     * @param  mixed $container
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
    }
}
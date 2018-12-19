<?php

namespace Ekyna\Component\Table\Bridge\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TwigPathCompilerPass
 * @package Ekyna\Component\Table\Bridge\Symfony\DependencyInjection;
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TwigPathCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('twig.loader.filesystem')) {
            $loader = $container->getDefinition('twig.loader.filesystem');

            $path = realpath(__DIR__.'/../../Twig/Resources/views');
            $loader->addMethodCall('addPath', [$path, 'table']);
        }
    }
}

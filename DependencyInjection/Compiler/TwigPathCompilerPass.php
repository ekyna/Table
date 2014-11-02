<?php

namespace Ekyna\Component\Table\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TwigPathCompilerPass
 * @package Ekyna\Component\Characteristics\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TwigPathCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('twig.loader.filesystem')) {
            $loader = $container->getDefinition('twig.loader.filesystem');

            $path = realpath(__DIR__.'/../../Resources/views');
            $loader->addMethodCall('addPath', array($path, 'table'));
        }
    }
}

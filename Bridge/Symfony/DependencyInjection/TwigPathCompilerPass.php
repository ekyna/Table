<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use function realpath;

/**
 * Class TwigPathCompilerPass
 * @package Ekyna\Component\Table\Bridge\Symfony\DependencyInjection;
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TwigPathCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $container
            ->getDefinition('twig.loader.native_filesystem')
            ->addMethodCall('addPath', [realpath(__DIR__ . '/../../Twig/Resources/views'), 'table']);
    }
}

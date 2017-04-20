<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class TableExtension
 * @package Ekyna\Component\Table\Bridge\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TableExtension extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'ekyna_table_render',
                [TableRenderer::class, 'renderTable'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ekyna_table_cell',
                [TableRenderer::class, 'renderCell'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ekyna_table_pager',
                [TableRenderer::class, 'renderPager'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}

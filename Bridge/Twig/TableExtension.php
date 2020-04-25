<?php

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
     * @var TwigRenderer
     */
    private $renderer;


    /**
     * Constructor.
     *
     * @param TwigRenderer $renderer
     */
    public function __construct(TwigRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'ekyna_table_render',
                [$this->renderer, 'render'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ekyna_table_cell',
                [$this->renderer, 'renderCell'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ekyna_table_pager',
                [$this->renderer, 'renderPager'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}

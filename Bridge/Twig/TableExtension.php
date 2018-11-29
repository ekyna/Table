<?php

namespace Ekyna\Component\Table\Bridge\Twig;

use Ekyna\Component\Table\View\TableView;
use Ekyna\Component\Table\View\CellView;
use Pagerfanta\View\ViewFactoryInterface;

/**
 * Class TableExtension
 * @package Ekyna\Component\Table\Bridge\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TableExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction(
                'ekyna_table_render',
                [$this->renderer, 'render'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'ekyna_table_cell',
                [$this->renderer, 'renderCell'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'ekyna_table_pager',
                [$this->renderer, 'renderPager'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}

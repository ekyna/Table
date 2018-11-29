<?php

namespace Ekyna\Component\Table\Bridge\Twig;

use Ekyna\Component\Table\View\CellView;
use Ekyna\Component\Table\View\TableView;
use Pagerfanta\View\ViewFactoryInterface;

/**
 * Class TwigRenderer
 * @package Ekyna\Component\Table\Bridge\Twig
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TwigRenderer
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var ViewFactoryInterface
     */
    private $viewFactory;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var \Twig_TemplateWrapper
     */
    protected $template;


    /**
     * Constructor.
     *
     * @param \Twig_Environment    $environment
     * @param ViewFactoryInterface $viewFactory
     * @param string               $template
     */
    public function __construct(\Twig_Environment $environment, ViewFactoryInterface $viewFactory, $template = null)
    {
        $this->environment = $environment;
        $this->viewFactory = $viewFactory;

        $this->defaults = [
            'class'    => null,
            'template' => '@EkynaTable/table.html.twig',
        ];

        if ($template) {
            $this->defaults['template'] = $template;
        }
    }

    /**
     * Renders a table.
     *
     * @param TableView $table
     * @param array     $options
     *
     * @return string
     */
    public function render(TableView $table, array $options = [])
    {
        $options = array_merge($this->defaults, $options);

        $template = $options['template'];
        if ($template instanceof \Twig_TemplateWrapper) {
            $this->template = $template;
        } else {
            $this->template = $this->environment->load($template);
        }

        return $this->template->renderBlock('table', ['table' => $table, 'options' => $options]);
    }

    /**
     * Renders a cell.
     *
     * @param CellView $cell
     *
     * @return string
     */
    public function renderCell(CellView $cell)
    {
        $name = $cell->vars['block_prefix'] . '_cell';

        return trim($this->template->renderBlock($name, $cell->vars));
    }

    /**
     * Renders pager.
     *
     * @param TableView $view
     * @param string    $viewName
     * @param array     $options
     *
     * @return string
     */
    public function renderPager(TableView $view, $viewName = 'twitter_bootstrap3', array $options = [])
    {
        if (!$view->pager) {
            return '';
        }

        $pageParam = $view->ui['page_name'];

        $options = array_merge([
            'pageParameter' => '[' . $pageParam . ']',
            'proximity'     => 3,
            'next_message'  => '&raquo;',
            'prev_message'  => '&laquo;',
            'default_view'  => 'default',
        ], $options);

        $routeGenerator = function ($page) use ($pageParam) {
            return '?' . $pageParam . '=' . $page;
        };

        return $this->viewFactory->get($viewName)->render($view->pager, $routeGenerator, $options);
    }
}

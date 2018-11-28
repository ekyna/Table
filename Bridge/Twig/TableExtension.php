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
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var ViewFactoryInterface
     */
    private $viewFactory;

    /**
     * @var \Twig_Template
     */
    private $template;

    /**
     * @var array
     */
    protected $defaultOptions;


    /**
     * Constructor.
     *
     * @param \Twig_Environment    $environment
     * @param ViewFactoryInterface $viewFactory
     * @param string               $template
     */
    public function __construct(\Twig_Environment $environment, ViewFactoryInterface $viewFactory, $template)
    {
        $this->environment = $environment;
        $this->viewFactory = $viewFactory;

        $this->defaultOptions = array_merge([
            'class'    => null,
            'template' => __DIR__ . '/../Resources/views/table.html.twig',
        ], [
            'template' => $template,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('ekyna_table_render', [$this, 'render'],      ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ekyna_table_cell',   [$this, 'renderCell'],  ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ekyna_table_pager',  [$this, 'renderPager'], ['is_safe' => ['html']]),
        ];
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
        $options = array_merge($this->defaultOptions, $options);

        $template = $options['template'];
        if ($template instanceof \Twig_Template) {
            $this->template = $template;
        } else {
            $this->template = $this->environment->loadTemplate($template);
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

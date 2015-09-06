<?php

namespace Ekyna\Component\Table\Twig;

use Ekyna\Component\Table\Util\ColumnSort;
use Ekyna\Component\Table\TableView;
use Ekyna\Component\Table\View\Cell;
use Ekyna\Component\Table\View\Column;
use Ekyna\Component\Table\View\AvailableFilter;
use Ekyna\Component\Table\View\ActiveFilter;
use Pagerfanta\View\ViewFactoryInterface;

/**
 * Class TableExtension
 * @package Ekyna\Component\Table\Twig
 * @author Étienne Dauvergne <contact@ekyna.com>
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
     * Constructor
     * 
     * @param \Twig_Environment    $environment
     * @param ViewFactoryInterface $viewFactory
     * @param string               $template
     */
    public function __construct(\Twig_Environment $environment, ViewFactoryInterface $viewFactory, $template)
    {
        $this->environment = $environment;
        $this->viewFactory = $viewFactory;
        $this->defaultOptions = array_merge(array(
            'class' => null,
            'template' => __DIR__.'/../Resources/views/table.html.twig',
        ), array(
        	'template' => $template
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('ekyna_table_render', array($this, 'render'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ekyna_table_cell', array($this, 'renderCell'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ekyna_table_pager', array($this, 'renderPager'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ekyna_table_sort_path', array($this, 'generateSortPath'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ekyna_table_filter_add_path', array($this, 'generateFilterAddPath'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ekyna_table_filter_remove_path', array($this, 'generateFilterRemovePath'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Renders a table
     *
     * @param TableView $table
     * @param array     $options
     *
     * @return string
     */
    public function render(TableView $table, array $options = array())
    {
        $options = array_merge($this->defaultOptions, $options);

        $template = $options['template'];
        if ($template instanceof \Twig_Template) {
            $this->template = $template;
        }else{
            $this->template = $this->environment->loadTemplate($template);
        }

        return $this->template->renderBlock('table', array('table' => $table, 'options' => $options));
    }

    /**
     * Renders a cell
     *
     * @param Cell $cell
     *
     * @return string
     */
    public function renderCell(Cell $cell)
    {
        $block = $cell->vars['type'].'_cell';
        /*if(!$this->template->hasBlock($block)) {
            $block = 'text_cell';
        }*/
        return trim($this->template->renderBlock($block, $cell->vars));
    }

    /**
     * Renders pager
     *
     * @param TableView $table
     * @param string    $viewName
     * @param array     $options
     *
     * @return string
     */
    public function renderPager(TableView $table, $viewName = 'twitter_bootstrap3', array $options = array())
    {
        $pageParam = $table->name.'_page';
        $options = array_merge(array(
            'pageParameter' => '['.$pageParam.']',
            'proximity'     => 3,
            'next_message'  => '&raquo;',
            'prev_message'  => '&laquo;',
            'default_view'  => 'default'
        ), $options);

        $routeGenerator = function($page) use ($pageParam) {
            return '?'.$pageParam.'='.$page;
        };
        
        return $this->viewFactory->get($viewName)->render($table->pager, $routeGenerator, $options);
    }

    /**
     * Generates a column sort path
     * 
     * @param Column $column
     * 
     * @return string
     */
    public function generateSortPath(Column $column)
    {
        if(true === $column->getVar('sortable')) {
            $sort = $column->getVar('sort_dir');
            $path = '?' . $column->getVar('full_name') .'_sort=';
            return $path .= $sort === ColumnSort::ASC ? ColumnSort::DESC : ColumnSort::ASC;
        }
        return '';
    }

    /**
     * Generates a filter add path
     * 
     * @param AvailableFilter $filter
     * 
     * @return string
     */
    public function generateFilterAddPath(AvailableFilter $filter)
    {
        return '?add_filter=' . $filter->getVar('full_name');
    }

    /**
     * Generates a filter remove path
     * 
     * @param ActiveFilter $filter
     * 
     * @return string
     */
    public function generateFilterRemovePath(ActiveFilter $filter)
    {
        return '?remove_filter=' . $filter->getVar('id');
    }

    /**
     * @return string
     */
    public function getName()
    {
    	'ekyna_table';
    }
}
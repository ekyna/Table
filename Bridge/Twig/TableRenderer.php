<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Bridge\Twig;

use Ekyna\Component\Table\View\CellView;
use Ekyna\Component\Table\View\TableView;
use Pagerfanta\View\ViewFactoryInterface;
use Twig\Environment;
use Twig\TemplateWrapper;

use function array_merge;
use function trim;

/**
 * Class TableRenderer
 * @package Ekyna\Component\Table\Bridge\Twig
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableRenderer
{
    private Environment          $environment;
    private ViewFactoryInterface $viewFactory;
    protected array              $defaults;
    protected TemplateWrapper    $template;

    public function __construct(Environment $environment, ViewFactoryInterface $viewFactory, string $template = null)
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
     * Renders the table.
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function renderTable(TableView $table, array $options = []): string
    {
        $options = array_merge($this->defaults, $options);

        $template = $options['template'];
        if ($template instanceof TemplateWrapper) {
            $this->template = $template;
        } else {
            /** @noinspection PhpUnhandledExceptionInspection */
            $this->template = $this->environment->load($template);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->template->renderBlock('table', ['table' => $table, 'options' => $options]);
    }

    /**
     * Renders the cell.
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function renderCell(CellView $cell): string
    {
        $name = $cell->vars['block_prefix'] . '_cell';

        /** @noinspection PhpUnhandledExceptionInspection */
        return trim($this->template->renderBlock($name, $cell->vars));
    }

    /**
     * Renders the pager.
     */
    public function renderPager(TableView $view, string $viewName = 'twitter_bootstrap3', array $options = []): string
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

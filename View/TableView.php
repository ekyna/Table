<?php

namespace Ekyna\Component\Table\View;

/**
 * Class TableView
 * @package Ekyna\Component\Table\View
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TableView
{
    /**
     * The variables.
     *
     * @var array
     */
    public $vars = [];

    /**
     * The ui variables.
     *
     * @var array
     */
    public $ui = [];

    /**
     * The available filters views.
     *
     * @var AvailableFilterView[]
     */
    public $available_filters = [];

    /**
     * The active filters views.
     *
     * @var ActiveFilterView[]
     */
    public $active_filters = [];

    /**
     * The current filter label.
     *
     * @var null|string
     */
    public $filter_label = null;

    /**
     * The current filter form view.
     *
     * @var null|\Symfony\Component\Form\FormView
     */
    public $filter_form = null;

    /**
     * The columns headers views.
     *
     * @var HeadView
     */
    public $heads = [];

    /**
     * The rows views.
     *
     * @var RowView[]
     */
    public $rows = [];

    /**
     * The pager instance.
     *
     * @var \Pagerfanta\Pagerfanta
     */
    public $pager;
}

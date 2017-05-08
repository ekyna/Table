<?php

namespace Ekyna\Component\Table\View;

/**
 * Class RowView
 * @package Ekyna\Component\Table\View
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class RowView
{
    /**
     * The variables assigned to this view.
     *
     * @var array
     */
    public $vars = [];

    /**
     * The data identifier (array index or entity id).
     *
     * @var string
     */
    public $identifier;

    /**
     * Whether the row is selected.
     *
     * @var bool
     */
    public $selected = false;

    /**
     * The cells views.
     *
     * @var CellView[]
     */
    public $cells = [];

    /**
     * The table view.
     *
     * @var TableView
     */
    public $table;


    /**
     * Constructor.
     *
     * @param TableView $table
     */
    public function __construct(TableView $table)
    {
        $this->table = $table;
    }
}

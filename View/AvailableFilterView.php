<?php

namespace Ekyna\Component\Table\View;

/**
 * Class AvailableFilterView
 * @package Ekyna\Component\Table\View
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AvailableFilterView
{
    /**
     * The variables assigned to this view.
     *
     * @var array
     */
    public $vars = [
        'name'      => null,
        'full_name' => null,
        'label'     => null,
    ];

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

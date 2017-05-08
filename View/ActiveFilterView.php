<?php

namespace Ekyna\Component\Table\View;

/**
 * Class ActiveFilterView
 * @package Ekyna\Component\Table\View
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ActiveFilterView
{
    /**
     * The variables assigned to this view.
     *
     * @var array
     */
    public $vars = [
        'name'      => null,
        'full_name' => null,
        'id'        => null,
        'field'     => null,
        'operator'  => null,
        'value'     => null,
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

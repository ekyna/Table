<?php

namespace Ekyna\Component\Table\View;

/**
 * Class CellView
 * @package Ekyna\Component\Table\View
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CellView
{
    /**
     * The variables assigned to this view.
     *
     * @var array
     */
    public $vars = [
        'value'  => null,
        'type'   => null,
        'sorted' => false,
    ];

    /**
     * The row view.
     *
     * @var RowView
     */
    public $row;


    /**
     * Constructor.
     *
     * @param RowView $row
     */
    public function __construct(RowView $row)
    {
        $this->row = $row;
    }
}

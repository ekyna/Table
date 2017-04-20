<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\View;

/**
 * Class CellView
 * @package Ekyna\Component\Table\View
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class CellView
{
    /**
     * The variables assigned to this view.
     */
    public array $vars = [
        'value'  => null,
        'type'   => null,
        'sorted' => false,
    ];

    /**
     * The row view.
     */
    public RowView $row;


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

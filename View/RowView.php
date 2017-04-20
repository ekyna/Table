<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\View;

/**
 * Class RowView
 * @package Ekyna\Component\Table\View
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class RowView
{
    /**
     * The variables assigned to this view.
     */
    public array $vars = [];

    /**
     * The data identifier (array index or entity id).
     */
    public string $identifier;

    /**
     * Whether the row is selected.
     */
    public bool $selected = false;

    /**
     * The cells views.
     *
     * @var CellView[]
     */
    public array $cells = [];

    /**
     * The table view.
     */
    public TableView $table;


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

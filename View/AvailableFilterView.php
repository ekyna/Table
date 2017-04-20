<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\View;

/**
 * Class AvailableFilterView
 * @package Ekyna\Component\Table\View
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class AvailableFilterView
{
    /**
     * The variables assigned to this view.
     */
    public array $vars = [
        'name'      => null,
        'full_name' => null,
        'label'     => null,
    ];

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

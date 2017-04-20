<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\View;

use Ekyna\Component\Table\Util\ColumnSort;

/**
 * Class HeadView
 * @package Ekyna\Component\Table\View
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class HeadView
{
    /**
     * The variables assigned to this view.
     */
    public array $vars = [
        'name'      => null,
        'full_name' => null,
        'label'     => null,
        'sortable'  => false,
        'sort_dir'  => ColumnSort::NONE,
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

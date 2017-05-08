<?php

namespace Ekyna\Component\Table\Column;

use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\View;

/**
 * Interface ColumnInterface
 * @package Ekyna\Component\Table\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ColumnInterface
{
    /**
     * Sets the table.
     *
     * @param TableInterface $table
     *
     * @return self
     */
    public function setTable(TableInterface $table);

    /**
     * Returns the table.
     *
     * @return TableInterface
     */
    public function getTable();

    /**
     * Returns the column's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the column's label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns the config.
     *
     * @return ColumnConfigInterface
     */
    public function getConfig();

    /**
     * Returns whether the column is sorted.
     *
     * @return bool
     */
    public function isSorted();

    /**
     * Returns the sort direction.
     *
     * @return string
     */
    public function getSortDirection();

    /**
     * Sets the sort direction.
     *
     * @param string $sort
     *
     * @return self
     */
    public function setSortDirection($sort);

    /**
     * Creates the column head view.
     *
     * @param View\TableView $tableView
     *
     * @return View\HeadView The view
     */
    public function createHeadView(View\TableView $tableView);

    /**
     * Creates the column cell view.
     *
     * @param View\RowView $rowView
     * @param RowInterface $row
     *
     * @return View\HeadView The view
     */
    public function createCellView(View\RowView $rowView, RowInterface $row);


    /**
     * Applies the active sort to the adapter.
     *
     * @param AdapterInterface $adapter
     * @param ActiveSort       $activeSort
     */
    public function applySort(AdapterInterface $adapter, ActiveSort $activeSort);
}

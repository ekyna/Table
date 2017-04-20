<?php

declare(strict_types=1);

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
     * @param TableInterface|null $table
     *
     * @return $this|ColumnInterface
     */
    public function setTable(TableInterface $table = null): ColumnInterface;

    /**
     * Returns the table.
     *
     * @return TableInterface|null
     */
    public function getTable(): ?TableInterface;

    /**
     * Returns the column's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the column's label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Returns the config.
     *
     * @return ColumnConfigInterface
     */
    public function getConfig(): ColumnConfigInterface;

    /**
     * Returns whether the column is sorted.
     *
     * @return bool
     */
    public function isSorted(): bool;

    /**
     * Returns the sort direction.
     *
     * @return string
     */
    public function getSortDirection(): string;

    /**
     * Sets the sort direction.
     *
     * @param string $sort
     *
     * @return $this|ColumnInterface
     */
    public function setSortDirection(string $sort): ColumnInterface;

    /**
     * Creates the column head view.
     *
     * @param View\TableView $tableView
     *
     * @return View\HeadView The view
     */
    public function createHeadView(View\TableView $tableView): View\HeadView;

    /**
     * Creates the column cell view.
     *
     * @param View\RowView $rowView
     * @param RowInterface $row
     *
     * @return View\CellView The view
     */
    public function createCellView(View\RowView $rowView, RowInterface $row): View\CellView;


    /**
     * Applies the active sort to the adapter.
     *
     * @param AdapterInterface $adapter
     * @param ActiveSort       $activeSort
     */
    public function applySort(AdapterInterface $adapter, ActiveSort $activeSort): void;
}

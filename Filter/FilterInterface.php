<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\View;
use Symfony\Component\Form\FormInterface;

/**
 * Interface FilterInterface
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface FilterInterface
{
    /**
     * Sets the table.
     *
     * @param TableInterface $table
     *
     * @return $this|FilterInterface
     */
    public function setTable(TableInterface $table): FilterInterface;

    /**
     * Returns the table.
     *
     * @return TableInterface
     */
    public function getTable(): TableInterface;

    /**
     * Returns the filter's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the filter's label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Returns the config.
     *
     * @return FilterConfigInterface
     */
    public function getConfig(): FilterConfigInterface;

    /**
     * Creates the available filter view.
     *
     * @param View\TableView $tableView
     *
     * @return View\AvailableFilterView
     */
    public function createAvailableView(View\TableView $tableView): View\AvailableFilterView;

    /**
     * Creates the active filter view.
     *
     * @param View\TableView $tableView
     * @param ActiveFilter   $activeFilter
     *
     * @return View\ActiveFilterView
     */
    public function createActiveView(View\TableView $tableView, ActiveFilter $activeFilter): View\ActiveFilterView;

    /**
     * Creates the filter form.
     *
     * @return FormInterface
     */
    public function createForm(): FormInterface;

    /**
     * Applies the active filter to the adapter.
     *
     * @param AdapterInterface $adapter
     * @param ActiveFilter     $activeFilter
     */
    public function applyFilter(AdapterInterface $adapter, ActiveFilter $activeFilter): void;
}

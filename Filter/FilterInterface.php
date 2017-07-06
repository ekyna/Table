<?php

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\View;

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
     * Returns the filter's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the filter's label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns the config.
     *
     * @return FilterConfigInterface
     */
    public function getConfig();

    /**
     * Creates the available filter view.
     *
     * @param View\TableView $tableView
     *
     * @return View\AvailableFilterView
     */
    public function createAvailableView(View\TableView $tableView);

    /**
     * Creates the active filter view.
     *
     * @param View\TableView $tableView
     * @param ActiveFilter   $activeFilter
     *
     * @return View\ActiveFilterView
     */
    public function createActiveView(View\TableView $tableView, ActiveFilter $activeFilter);

    /**
     * Creates the filter form.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm();

    /**
     * Applies the active filter to the adapter.
     *
     * @param AdapterInterface $adapter
     * @param ActiveFilter     $activeFilter
     */
    public function applyFilter(AdapterInterface $adapter, ActiveFilter $activeFilter);
}

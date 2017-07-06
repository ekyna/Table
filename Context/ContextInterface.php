<?php

namespace Ekyna\Component\Table\Context;

use Symfony\Component\Form\FormInterface;

/**
 * Interface ContextInterface
 * @package Ekyna\Component\Table\Context
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ContextInterface
{
    /**
     * Resets the context.
     *
     * @return self
     */
    public function reset();

    /**
     * Returns whether the context is at its default state.
     *
     * @return bool
     */
    public function isDefault();

    /**
     * Returns the maximum rows per page.
     *
     * @return int
     */
    public function getMaxPerPage();

    /**
     * Sets the maximum rows per page.
     *
     * @param int $max
     *
     * @return int
     */
    public function setMaxPerPage($max);

    /**
     * Returns the current page number.
     *
     * @return int
     */
    public function getCurrentPage();

    /**
     * Sets the current page number.
     *
     * @param int $num
     *
     * @return self
     */
    public function setCurrentPage($num);

    /**
     * Returns the visible columns.
     *
     * @return array|\string[]
     */
    public function getVisibleColumns();

    /**
     * Returns the active sort.
     *
     * @return ActiveSort|null
     */
    public function getActiveSort();

    /**
     * Sets the visible columns.
     *
     * @param array|\string[] $names
     *
     * @return Context
     */
    public function setVisibleColumns(array $names);

    /**
     * Sets the active sort.
     *
     * @param ActiveSort|null $sort
     *
     * @return self
     */
    public function setActiveSort(ActiveSort $sort = null);

    /**
     * Returns the activeFilters.
     *
     * @return ActiveFilter[]
     */
    public function getActiveFilters();

    /**
     * Returns whether an active filter exists for the given id.
     *
     * @param string $id
     *
     * @return bool
     */
    public function hasActiveFilter($id);

    /**
     * Adds the active filter.
     *
     * @param ActiveFilter $filter
     *
     * @return self
     */
    public function addActiveFilter(ActiveFilter $filter);

    /**
     * Removes the active filter by its id.
     *
     * @param string $id
     *
     * @return self
     */
    public function removeActiveFilter($id);

    /**
     * Returns the selected rows identifiers.
     *
     * @return array
     */
    public function getSelectedIdentifiers();

    /**
     * Sets the selected rows identifiers.
     *
     * @param array $identifiers
     *
     * @return Context
     */
    public function setSelectedIdentifiers(array $identifiers);

    /**
     * Returns the filter label.
     *
     * @return string
     */
    public function getFilterLabel();

    /**
     * Sets the filter label.
     *
     * @param string $filterLabel
     *
     * @return self
     */
    public function setFilterLabel($filterLabel);

    /**
     * Returns the active filter form.
     *
     * @return FormInterface
     */
    public function getFilterForm();

    /**
     * Sets the active filter form.
     *
     * @param FormInterface $filterForm
     *
     * @return self
     */
    public function setFilterForm(FormInterface $filterForm);

    /**
     * Returns whether all rows should be processed.
     *
     * @return bool
     */
    public function getAll();

    /**
     * Sets whether all rows should be processed.
     *
     * @param bool $all
     *
     * @return Context
     */
    public function setAll($all);

    /**
     * Returns the selected action.
     *
     * @return string
     */
    public function getAction();

    /**
     * Sets the selected action.
     *
     * @param string $name
     */
    public function setAction($name);

    /**
     * Returns the selected export format.
     *
     * @return string
     */
    public function getFormat();

    /**
     * Sets the selected export format.
     *
     * @param string $format
     *
     * @return Context
     */
    public function setFormat($format);

    /**
     * Returns whether at least one row is selected.
     *
     * @return bool
     */
    public function hasSelection();

    /**
     * Returns the array representation of the context.
     *
     * @return array
     */
    public function toArray();

    /**
     * Loads the context from the given data array.
     *
     * @param array $data
     */
    public function fromArray(array $data);
}

<?php

declare(strict_types=1);

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
     * @return $this|ContextInterface
     */
    public function reset(): ContextInterface;

    /**
     * Returns whether the context is at its default state.
     *
     * @return bool
     */
    public function isDefault(): bool;

    /**
     * Returns the maximum rows per page.
     *
     * @return int
     */
    public function getMaxPerPage(): int;

    /**
     * Sets the maximum rows per page.
     *
     * @param int $max
     *
     * @return $this|ContextInterface
     */
    public function setMaxPerPage(int $max): ContextInterface;

    /**
     * Returns the current page number.
     *
     * @return int
     */
    public function getCurrentPage(): int;

    /**
     * Sets the current page number.
     *
     * @param int $num
     *
     * @return $this|ContextInterface
     */
    public function setCurrentPage(int $num): ContextInterface;

    /**
     * Returns the visible columns.
     *
     * @return array|string[]
     */
    public function getVisibleColumns(): array;

    /**
     * Returns the active sort.
     *
     * @return ActiveSort|null
     */
    public function getActiveSort(): ?ActiveSort;

    /**
     * Sets the visible columns.
     *
     * @param array|string[] $names
     *
     * @return $this|ContextInterface
     */
    public function setVisibleColumns(array $names): ContextInterface;

    /**
     * Sets the active sort.
     *
     * @param ActiveSort|null $sort
     *
     * @return $this|ContextInterface
     */
    public function setActiveSort(ActiveSort $sort = null): ContextInterface;

    /**
     * Returns the activeFilters.
     *
     * @return ActiveFilter[]
     */
    public function getActiveFilters(): array;

    /**
     * Returns whether an active filter exists for the given id.
     *
     * @param string $id
     *
     * @return bool
     */
    public function hasActiveFilter(string $id): bool;

    /**
     * Adds the active filter.
     *
     * @param ActiveFilter $filter
     *
     * @return $this|ContextInterface
     */
    public function addActiveFilter(ActiveFilter $filter): ContextInterface;

    /**
     * Removes the active filter by its id.
     *
     * @param string $id
     *
     * @return $this|ContextInterface
     */
    public function removeActiveFilter(string $id): ContextInterface;

    /**
     * Returns the selected rows identifiers.
     *
     * @return array
     */
    public function getSelectedIdentifiers(): array;

    /**
     * Sets the selected rows identifiers.
     *
     * @param array $identifiers
     *
     * @return $this|ContextInterface
     */
    public function setSelectedIdentifiers(array $identifiers): ContextInterface;

    /**
     * Returns the filter label.
     *
     * @return string
     */
    public function getFilterLabel(): string;

    /**
     * Sets the filter label.
     *
     * @param string $label
     *
     * @return $this|ContextInterface
     */
    public function setFilterLabel(string $label): ContextInterface;

    /**
     * Returns the active filter form.
     *
     * @return FormInterface|null
     */
    public function getFilterForm(): ?FormInterface;

    /**
     * Sets the active filter form.
     *
     * @param FormInterface|null $form
     *
     * @return $this|ContextInterface
     */
    public function setFilterForm(?FormInterface $form): ContextInterface;

    /**
     * Returns whether all rows should be processed.
     *
     * @return bool
     */
    public function getAll(): bool;

    /**
     * Sets whether all rows should be processed.
     *
     * @param bool $all
     *
     * @return $this|ContextInterface
     */
    public function setAll(bool $all): ContextInterface;

    /**
     * Returns the selected action.
     *
     * @return string
     */
    public function getAction(): string;

    /**
     * Sets the selected action.
     *
     * @param string $name
     *
     * @return $this|ContextInterface
     */
    public function setAction(string $name): ContextInterface;

    /**
     * Returns the selected export format.
     *
     * @return string
     */
    public function getFormat(): string;

    /**
     * Sets the selected export format.
     *
     * @param string $format
     *
     * @return $this|ContextInterface
     */
    public function setFormat(string $format): ContextInterface;

    /**
     * Returns whether at least one row is selected.
     *
     * @return bool
     */
    public function hasSelection(): bool;

    /**
     * Returns the array representation of the context.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Loads the context from the given data array.
     *
     * @param array $data
     */
    public function fromArray(array $data): void;
}

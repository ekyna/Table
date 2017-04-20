<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

use OutOfBoundsException;

/**
 * Interface TableInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface TableInterface
{
    /**
     * Adds or replaces a column to the table.
     *
     * @param Column\ColumnInterface|string          $column  The ColumnInterface instance or the name of the column
     * @param Column\ColumnTypeInterface|string|null $type    The column's type, if a name was passed
     * @param array                                  $options The column's options, if a name was passed
     *
     * @return $this|TableInterface
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     * @throws Exception\UnexpectedTypeException If $column or $type has an unexpected type.
     */
    public function addColumn($column, $type = null, array $options = []): TableInterface;

    /**
     * Returns the column with the given name.
     *
     * @param string $name The name of the column
     *
     * @return Column\ColumnInterface
     *
     * @throws OutOfBoundsException If the named column does not exist.
     */
    public function getColumn(string $name): Column\ColumnInterface;

    /**
     * Returns whether a column with the given name exists.
     *
     * @param string $name The name of the column
     *
     * @return bool
     */
    public function hasColumn(string $name): bool;

    /**
     * Removes a column from the table.
     *
     * @param string $name The name of the column to column
     *
     * @return $this|TableInterface
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     */
    public function removeColumn(string $name): TableInterface;

    /**
     * Returns the columns.
     *
     * @return Column\ColumnInterface[]
     */
    public function getColumns(): array;

    /**
     * Adds or replaces a filter to the table.
     *
     * @param Filter\FilterInterface|string          $filter  The FilterInterface instance or the name of the filter
     * @param Filter\FilterTypeInterface|string|null $type    The filter's type, if a name was passed
     * @param array                                  $options The filter's options, if a name was passed
     *
     * @return $this|TableInterface
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     * @throws Exception\UnexpectedTypeException If $filter or $type has an unexpected type.
     */
    public function addFilter($filter, $type = null, array $options = []): TableInterface;

    /**
     * Returns the filter with the given name.
     *
     * @param string $name The name of the filter
     *
     * @return Filter\FilterInterface
     *
     * @throws OutOfBoundsException If the named filter does not exist.
     */
    public function getFilter(string $name): Filter\FilterInterface;

    /**
     * Returns whether a filter with the given name exists.
     *
     * @param string $name The name of the filter
     *
     * @return bool
     */
    public function hasFilter(string $name): bool;

    /**
     * Removes a filter from the table.
     *
     * @param string $name The name of the filter to filter
     *
     * @return $this|TableInterface
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     */
    public function removeFilter(string $name): TableInterface;

    /**
     * Returns the filters.
     *
     * @return array|Filter\FilterInterface[]
     */
    public function getFilters(): array;

    /**
     * Adds or replaces a action to the table.
     *
     * @param Action\ActionInterface|string          $action  The ActionInterface instance or the name of the action
     * @param Action\ActionTypeInterface|string|null $type    The action's type, if a name was passed
     * @param array                                  $options The action's options, if a name was passed
     *
     * @return $this|TableInterface
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     * @throws Exception\UnexpectedTypeException If $action or $type has an unexpected type.
     */
    public function addAction($action, $type = null, array $options = []): TableInterface;

    /**
     * Returns the action with the given name.
     *
     * @param string $name The name of the action
     *
     * @return Action\ActionInterface
     *
     * @throws OutOfBoundsException If the named action does not exist.
     */
    public function getAction(string $name): Action\ActionInterface;

    /**
     * Returns whether a action with the given name exists.
     *
     * @param string $name The name of the action
     *
     * @return bool
     */
    public function hasAction(string $name): bool;

    /**
     * Removes a action from the table.
     *
     * @param string $name The name of the action to action
     *
     * @return $this|TableInterface
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     */
    public function removeAction(string $name): TableInterface;

    /**
     * Returns the actions.
     *
     * @return array|Action\ActionInterface[]
     */
    public function getActions(): array;

    /**
     * Adds the table error.
     *
     * @param TableError $error
     *
     * @return $this|TableInterface
     */
    public function addError(TableError $error): TableInterface;

    /**
     * Returns the errors.
     *
     * @return TableError[]
     */
    public function getErrors(): array;

    /**
     * Returns the table's configuration.
     *
     * @return TableConfigInterface
     */
    public function getConfig(): TableConfigInterface;

    /**
     * Returns the table's hash.
     *
     * @return string
     */
    public function getHash(): string;

    /**
     * Returns the table's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Handles the request.
     *
     * @param object|null $request The request to handle
     *
     * @return object|null The handler response
     */
    public function handleRequest(object $request = null): ?object;

    /**
     * Returns the context.
     *
     * @return Context\ContextInterface
     */
    public function getContext(): Context\ContextInterface;

    /**
     * Returns the parameters helper.
     *
     * @return Http\ParametersHelper
     */
    public function getParametersHelper(): Http\ParametersHelper;

    /**
     * Returns the source adapter.
     *
     * @return Source\AdapterInterface
     */
    public function getSourceAdapter(): Source\AdapterInterface;

    /**
     * Creates the table view.
     *
     * @return View\TableView The view
     */
    public function createView(): View\TableView;
}

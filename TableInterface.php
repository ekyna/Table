<?php

namespace Ekyna\Component\Table;

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
     * @param Column\ColumnInterface|string $column  The ColumnInterface instance or the name of the column
     * @param string|null                   $type    The column's type, if a name was passed
     * @param array                         $options The column's options, if a name was passed
     *
     * @return self
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     * @throws Exception\UnexpectedTypeException If $column or $type has an unexpected type.
     */
    public function addColumn($column, $type = null, array $options = []);

    /**
     * Returns the column with the given name.
     *
     * @param string $name The name of the column
     *
     * @return Column\ColumnInterface
     *
     * @throws \OutOfBoundsException If the named column does not exist.
     */
    public function getColumn($name);

    /**
     * Returns whether a column with the given name exists.
     *
     * @param string $name The name of the column
     *
     * @return bool
     */
    public function hasColumn($name);

    /**
     * Removes a column from the table.
     *
     * @param string $name The name of the column to column
     *
     * @return self
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     */
    public function removeColumn($name);

    /**
     * Returns the columns.
     *
     * @return Column\ColumnInterface[]
     */
    public function getColumns();

    /**
     * Adds or replaces a filter to the table.
     *
     * @param Filter\FilterInterface|string $filter  The FilterInterface instance or the name of the filter
     * @param string|null                   $type    The filter's type, if a name was passed
     * @param array                         $options The filter's options, if a name was passed
     *
     * @return self
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     * @throws Exception\UnexpectedTypeException If $filter or $type has an unexpected type.
     */
    public function addFilter($filter, $type = null, array $options = []);

    /**
     * Returns the filter with the given name.
     *
     * @param string $name The name of the filter
     *
     * @return Filter\FilterInterface
     *
     * @throws \OutOfBoundsException If the named filter does not exist.
     */
    public function getFilter($name);

    /**
     * Returns whether a filter with the given name exists.
     *
     * @param string $name The name of the filter
     *
     * @return bool
     */
    public function hasFilter($name);

    /**
     * Removes a filter from the table.
     *
     * @param string $name The name of the filter to filter
     *
     * @return self
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     */
    public function removeFilter($name);

    /**
     * Returns the filters.
     *
     * @return array|Filter\FilterInterface[]
     */
    public function getFilters();

    /**
     * Adds or replaces a action to the table.
     *
     * @param Action\ActionInterface|string $action  The ActionInterface instance or the name of the action
     * @param string|null                   $type    The action's type, if a name was passed
     * @param array                         $options The action's options, if a name was passed
     *
     * @return self
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     * @throws Exception\UnexpectedTypeException If $action or $type has an unexpected type.
     */
    public function addAction($action, $type = null, array $options = []);

    /**
     * Returns the action with the given name.
     *
     * @param string $name The name of the action
     *
     * @return Action\ActionInterface
     *
     * @throws \OutOfBoundsException If the named action does not exist.
     */
    public function getAction($name);

    /**
     * Returns whether a action with the given name exists.
     *
     * @param string $name The name of the action
     *
     * @return bool
     */
    public function hasAction($name);

    /**
     * Removes a action from the table.
     *
     * @param string $name The name of the action to action
     *
     * @return self
     *
     * @throws Exception\BadMethodCallException If the table is locked.
     */
    public function removeAction($name);

    /**
     * Returns the actions.
     *
     * @return array|Action\ActionInterface[]
     */
    public function getActions();

    /**
     * Adds the table error.
     *
     * @param TableError $error
     *
     * @return self
     */
    public function addError(TableError $error);

    /**
     * Returns the errors.
     *
     * @return TableError[]
     */
    public function getErrors();

    /**
     * Returns the table's configuration.
     *
     * @return TableConfigInterface
     */
    public function getConfig();

    /**
     * Returns the table's hash.
     *
     * @return string
     */
    public function getHash();

    /**
     * Returns the table's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Handles the request.
     *
     * @param mixed $request The request to handle
     *
     * @return mixed The handler response
     */
    public function handleRequest($request = null);

    /**
     * Returns the context.
     *
     * @return Context\ContextInterface
     */
    public function getContext();

    /**
     * Returns the parameters helper.
     *
     * @return Http\ParametersHelper
     */
    public function getParametersHelper();

    /**
     * Returns the source adapter.
     *
     * @return Source\AdapterInterface
     */
    public function getSourceAdapter();

    /**
     * Creates the table view.
     *
     * @return View\TableView The view
     */
    public function createView();
}

<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

/**
 * Interface TableBuilderInterface
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface TableBuilderInterface extends TableConfigBuilderInterface
{
    /**
     * Adds a column definition
     *
     * @param Column\ColumnInterface|string $column
     * @param string|null                   $type
     * @param array                         $options
     *
     * @return $this|TableBuilderInterface
     *
     * @throws Exception\InvalidArgumentException
     */
    public function addColumn($column, string $type = null, array $options = []): TableBuilderInterface;

    /**
     * Creates a column builder.
     *
     * @param string      $name    The name of the column or the name of the property
     * @param string|null $type    The type of the column or null if name is a property
     * @param array       $options The options
     *
     * @return Column\ColumnBuilderInterface
     */
    public function createColumn(string $name, string $type = null, array $options = []): Column\ColumnBuilderInterface;

    /**
     * Returns a column by name.
     *
     * @param string $name The name of the column
     *
     * @return Column\ColumnBuilderInterface
     *
     * @throws Exception\InvalidArgumentException if the given column does not exist
     */
    public function getColumn(string $name): Column\ColumnBuilderInterface;

    /**
     * Removes the column by name.
     *
     * @param string $name
     *
     * @return $this|TableBuilderInterface
     *
     * @throws Exception\InvalidArgumentException
     */
    public function removeColumn(string $name): TableBuilderInterface;

    /**
     * Adds a filter definition
     *
     * @param Filter\FilterInterface|string $filter
     * @param string|null                   $type
     * @param array                         $options
     *
     * @return $this|TableBuilderInterface
     *
     * @throws Exception\InvalidArgumentException
     */
    public function addFilter($filter, string $type = null, array $options = []): TableBuilderInterface;

    /**
     * Creates a filter builder.
     *
     * @param string      $name    The name of the filter or the name of the property
     * @param string|null $type    The type of the filter or null if name is a property
     * @param array       $options The options
     *
     * @return Filter\FilterBuilderInterface
     */
    public function createFilter(string $name, string $type = null, array $options = []): Filter\FilterBuilderInterface;

    /**
     * Returns a filter by name.
     *
     * @param string $name The name of the filter
     *
     * @return Filter\FilterBuilderInterface
     *
     * @throws Exception\InvalidArgumentException if the given filter does not exist
     */
    public function getFilter(string $name): Filter\FilterBuilderInterface;

    /**
     * Removes the filter by name.
     *
     * @param string $name
     *
     * @return $this|TableBuilderInterface
     *
     * @throws Exception\InvalidArgumentException
     */
    public function removeFilter(string $name): TableBuilderInterface;

    /**
     * Adds a action definition
     *
     * @param Action\ActionInterface|string $action
     * @param string|null                   $type
     * @param array                         $options
     *
     * @return $this|TableBuilderInterface
     *
     * @throws Exception\InvalidArgumentException
     */
    public function addAction($action, string $type = null, array $options = []): TableBuilderInterface;

    /**
     * Creates a action builder.
     *
     * @param string      $name    The name of the action or the name of the property
     * @param string|null $type    The type of the action or null if name is a property
     * @param array       $options The options
     *
     * @return Action\ActionBuilderInterface
     */
    public function createAction(string $name, string $type = null, array $options = []): Action\ActionBuilderInterface;

    /**
     * Returns a action by name.
     *
     * @param string $name The name of the action
     *
     * @return Action\ActionBuilderInterface
     *
     * @throws Exception\InvalidArgumentException if the given action does not exist
     */
    public function getAction(string $name): Action\ActionBuilderInterface;

    /**
     * Removes the action by name.
     *
     * @param string $name
     *
     * @return $this|TableBuilderInterface
     *
     * @throws Exception\InvalidArgumentException
     */
    public function removeAction(string $name): TableBuilderInterface;

    /**
     * Creates the table.
     *
     * @return TableInterface
     */
    public function getTable(): TableInterface;
}

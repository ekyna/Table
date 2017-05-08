<?php

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
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return $this|TableBuilderInterface
     */
    public function addColumn($name, $type = null, array $options = []);

    /**
     * Creates a column builder.
     *
     * @param string      $name    The name of the column or the name of the property
     * @param string|null $type    The type of the column or null if name is a property
     * @param array       $options The options
     *
     * @return self
     */
    public function createColumn($name, $type = null, array $options = []);

    /**
     * Returns a column by name.
     *
     * @param string $name The name of the column
     *
     * @return self
     *
     * @throws Exception\InvalidArgumentException if the given column does not exist
     */
    public function getColumn($name);

    /**
     * Removes the column by name.
     *
     * @param string $name
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return $this|TableBuilderInterface
     */
    public function removeColumn($name);

    /**
     * Adds a filter definition
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return $this|TableBuilderInterface
     */
    public function addFilter($name, $type = null, array $options = []);

    /**
     * Creates a filter builder.
     *
     * @param string      $name    The name of the filter or the name of the property
     * @param string|null $type    The type of the filter or null if name is a property
     * @param array       $options The options
     *
     * @return self
     */
    public function createFilter($name, $type = null, array $options = []);

    /**
     * Returns a filter by name.
     *
     * @param string $name The name of the filter
     *
     * @return self
     *
     * @throws Exception\InvalidArgumentException if the given filter does not exist
     */
    public function getFilter($name);

    /**
     * Removes the filter by name.
     *
     * @param string $name
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return $this|TableBuilderInterface
     */
    public function removeFilter($name);

    /**
     * Adds a action definition
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return $this|TableBuilderInterface
     */
    public function addAction($name, $type = null, array $options = []);

    /**
     * Creates a action builder.
     *
     * @param string      $name    The name of the action or the name of the property
     * @param string|null $type    The type of the action or null if name is a property
     * @param array       $options The options
     *
     * @return self
     */
    public function createAction($name, $type = null, array $options = []);

    /**
     * Returns a action by name.
     *
     * @param string $name The name of the action
     *
     * @return self
     *
     * @throws Exception\InvalidArgumentException if the given action does not exist
     */
    public function getAction($name);

    /**
     * Removes the action by name.
     *
     * @param string $name
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return $this|TableBuilderInterface
     */
    public function removeAction($name);

    /**
     * Creates the table.
     *
     * @return TableInterface
     */
    public function getTable();
}

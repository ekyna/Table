<?php

namespace Ekyna\Component\Table;

/**
 * Interface TableBuilderInterface
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface TableBuilderInterface
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
     * Returns the Table
     *
     * @throws Exception\RuntimeException
     *
     * @return Table
     */
    public function getTable();
}

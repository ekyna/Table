<?php

namespace Ekyna\Component\Table;

/**
 * Interface TableExtensionInterface
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface TableExtensionInterface
{
    /**
     * Returns a table type by name.
     *
     * @param string $name The name of the table type
     *
     * @return ColumnTypeInterface The table type
     *
     * @throws Exception\InvalidArgumentException if the given column type is not supported by this extension
     */
    public function getTableType($name);

    /**
     * Returns whether the given table type is supported.
     *
     * @param string $name The name of the table type
     *
     * @return Boolean Whether the table type is supported by this extension
     */
    public function hasTableType($name);

    /**
     * Returns a column type by name.
     *
     * @param string $name The name of the column type
     *
     * @return ColumnTypeInterface The column type
     *
     * @throws Exception\InvalidArgumentException if the given column type is not supported by this extension
     */
    public function getColumnType($name);

    /**
     * Returns whether the given column type is supported.
     *
     * @param string $name The name of the column type
     *
     * @return Boolean Whether the column type is supported by this extension
     */
    public function hasColumnType($name);

    /**
     * Returns a filter type by name.
     *
     * @param string $name The name of the filter type
     *
     * @return FilterTypeInterface The type
     *
     * @throws Exception\InvalidArgumentException if the given type is not supported by this extension
     */
    public function getFilterType($name);

    /**
     * Returns whether the given filter type is supported.
     *
     * @param string $name The name of the filter type
     *
     * @return Boolean Whether the filter type is supported by this extension
     */
    public function hasFilterType($name);
}
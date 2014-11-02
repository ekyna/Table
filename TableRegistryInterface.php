<?php

namespace Ekyna\Component\Table;

/**
 * Interface TableRegistryInterface
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface TableRegistryInterface
{
    /**
     * Returns a column type by name.
     *
     * This methods registers the type extensions from the form extensions.
     *
     * @param string $name The name of the type
     *
     * @return ColumnTypeInterface The type
     *
     * @throws Exception\UnexpectedTypeException  if the passed name is not a string
     * @throws Exception\InvalidArgumentException if the type can not be retrieved from any extension
     */
    public function getColumnType($name);

    /**
     * Returns whether the given column type is supported.
     *
     * @param string $name The name of the type
     *
     * @return Boolean Whether the type is supported
    */
    public function hasColumnType($name);

    /**
     * Returns a filter type by name.
     *
     * This methods registers the type extensions from the form extensions.
     *
     * @param string $name The name of the type
     *
     * @return FilterTypeInterface The type
     *
     * @throws Exception\UnexpectedTypeException  if the passed name is not a string
     * @throws Exception\InvalidArgumentException if the type can not be retrieved from any extension
     */
    public function getFilterType($name);

    /**
     * Returns whether the given filter type is supported.
     *
     * @param string $name The name of the type
     *
     * @return Boolean Whether the type is supported
    */
    public function hasFilterType($name);

    /**
     * Returns the extensions loaded by the framework.
     *
     * @return TableExtensionInterface[]
     */
    public function getExtensions();
}
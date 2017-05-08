<?php

namespace Ekyna\Component\Table\Extension;

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
     * @return \Ekyna\Component\Table\TableTypeInterface The table type
     *
     * @throws \Ekyna\Component\Table\Exception\InvalidArgumentException If the given table type is not supported by this extension
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
     * Returns the extensions for the given table type.
     *
     * @param string $name The name of the table type
     *
     * @return TableTypeExtensionInterface[] An array of extensions as TableTypeExtensionInterface instances
     */
    public function getTableTypeExtensions($name);

    /**
     * Returns whether this extension provides type extensions for the given table type.
     *
     * @param string $name The name of the table type
     *
     * @return bool Whether the given type has extensions
     */
    public function hasTableTypeExtensions($name);

    /**
     * Returns a column type by name.
     *
     * @param string $name The name of the column type
     *
     * @return \Ekyna\Component\Table\Column\ColumnTypeInterface The column type
     *
     * @throws \Ekyna\Component\Table\Exception\InvalidArgumentException If the given column type is not provided by this extension
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
     * Returns the extensions for the given column type.
     *
     * @param string $name The name of the column type
     *
     * @return ColumnTypeExtensionInterface[] An array of extensions as ColumnTypeExtensionInterface instances
     */
    public function getColumnTypeExtensions($name);

    /**
     * Returns whether this extension provides type extensions for the given column type.
     *
     * @param string $name The name of the column type
     *
     * @return bool Whether the given type has extensions
     */
    public function hasColumnTypeExtensions($name);

    /**
     * Returns a filter type by name.
     *
     * @param string $name The name of the filter type
     *
     * @return \Ekyna\Component\Table\Filter\FilterTypeInterface The type
     *
     * @throws \Ekyna\Component\Table\Exception\InvalidArgumentException If the given type is not provided by this extension
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

    /**
     * Returns the extensions for the given filter type.
     *
     * @param string $name The name of the filter type
     *
     * @return FilterTypeExtensionInterface[] An array of extensions as FilterTypeExtensionInterface instances
     */
    public function getFilterTypeExtensions($name);

    /**
     * Returns whether this extension provides type extensions for the given filter type.
     *
     * @param string $name The name of the filter type
     *
     * @return bool Whether the given type has extensions
     */
    public function hasFilterTypeExtensions($name);

    /**
     * Returns a action type by name.
     *
     * @param string $name The name of the action type
     *
     * @return \Ekyna\Component\Table\Action\ActionTypeInterface The type
     *
     * @throws \Ekyna\Component\Table\Exception\InvalidArgumentException If the given type is not provided by this extension
     */
    public function getActionType($name);

    /**
     * Returns whether the given action type is supported.
     *
     * @param string $name The name of the action type
     *
     * @return Boolean Whether the action type is supported by this extension
     */
    public function hasActionType($name);

    /**
     * Returns the extensions for the given action type.
     *
     * @param string $name The name of the action type
     *
     * @return ActionTypeExtensionInterface[] An array of extensions as ActionTypeExtensionInterface instances
     */
    public function getActionTypeExtensions($name);

    /**
     * Returns whether this extension provides type extensions for the given action type.
     *
     * @param string $name The name of the action type
     *
     * @return bool Whether the given type has extensions
     */
    public function hasActionTypeExtensions($name);

    /**
     * Returns the adapter factory by name.
     *
     * @param string $name The name of the adapter
     *
     * @return \Ekyna\Component\Table\Source\AdapterFactoryInterface The adapter factory
     *
     * @throws \Ekyna\Component\Table\Exception\InvalidArgumentException If the given adapter is not provided by this extension
     */
    public function getAdapterFactory($name);

    /**
     * Returns whether this extension provides the adapter factory for the given name.
     *
     * @param string $name The name of the adapter factory
     *
     * @return Boolean Whether the adapter is supported by this extension
     */
    public function hasAdapterFactory($name);
}

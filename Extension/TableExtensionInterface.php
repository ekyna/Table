<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\Action\ActionTypeInterface;
use Ekyna\Component\Table\Column\ColumnTypeInterface;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Filter\FilterTypeInterface;
use Ekyna\Component\Table\Source\AdapterFactoryInterface;
use Ekyna\Component\Table\TableTypeInterface;

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
     * @return TableTypeInterface The table type
     *
     * @throws InvalidArgumentException If the given table type is not supported by this extension
     */
    public function getTableType(string $name): TableTypeInterface;

    /**
     * Returns whether the given table type is supported.
     *
     * @param string $name The name of the table type
     *
     * @return bool Whether the table type is supported by this extension
     */
    public function hasTableType(string $name): bool;

    /**
     * Returns the extensions for the given table type.
     *
     * @param string $name The name of the table type
     *
     * @return TableTypeExtensionInterface[] An array of extensions as TableTypeExtensionInterface instances
     */
    public function getTableTypeExtensions(string $name): array;

    /**
     * Returns whether this extension provides type extensions for the given table type.
     *
     * @param string $name The name of the table type
     *
     * @return bool Whether the given type has extensions
     */
    public function hasTableTypeExtensions(string $name): bool;

    /**
     * Returns a column type by name.
     *
     * @param string $name The name of the column type
     *
     * @return ColumnTypeInterface The column type
     *
     * @throws InvalidArgumentException If the given column type is not provided by this extension
     */
    public function getColumnType(string $name): ColumnTypeInterface;

    /**
     * Returns whether the given column type is supported.
     *
     * @param string $name The name of the column type
     *
     * @return bool Whether the column type is supported by this extension
     */
    public function hasColumnType(string $name): bool;

    /**
     * Returns the extensions for the given column type.
     *
     * @param string $name The name of the column type
     *
     * @return ColumnTypeExtensionInterface[] An array of extensions as ColumnTypeExtensionInterface instances
     */
    public function getColumnTypeExtensions(string $name): array;

    /**
     * Returns whether this extension provides type extensions for the given column type.
     *
     * @param string $name The name of the column type
     *
     * @return bool Whether the given type has extensions
     */
    public function hasColumnTypeExtensions(string $name): bool;

    /**
     * Returns a filter type by name.
     *
     * @param string $name The name of the filter type
     *
     * @return FilterTypeInterface The type
     *
     * @throws InvalidArgumentException If the given type is not provided by this extension
     */
    public function getFilterType(string $name): FilterTypeInterface;

    /**
     * Returns whether the given filter type is supported.
     *
     * @param string $name The name of the filter type
     *
     * @return bool Whether the filter type is supported by this extension
     */
    public function hasFilterType(string $name): bool;

    /**
     * Returns the extensions for the given filter type.
     *
     * @param string $name The name of the filter type
     *
     * @return FilterTypeExtensionInterface[] An array of extensions as FilterTypeExtensionInterface instances
     */
    public function getFilterTypeExtensions(string $name): array;

    /**
     * Returns whether this extension provides type extensions for the given filter type.
     *
     * @param string $name The name of the filter type
     *
     * @return bool Whether the given type has extensions
     */
    public function hasFilterTypeExtensions(string $name): bool;

    /**
     * Returns a action type by name.
     *
     * @param string $name The name of the action type
     *
     * @return ActionTypeInterface The type
     *
     * @throws InvalidArgumentException If the given type is not provided by this extension
     */
    public function getActionType(string $name): ActionTypeInterface;

    /**
     * Returns whether the given action type is supported.
     *
     * @param string $name The name of the action type
     *
     * @return bool Whether the action type is supported by this extension
     */
    public function hasActionType(string $name): bool;

    /**
     * Returns the extensions for the given action type.
     *
     * @param string $name The name of the action type
     *
     * @return ActionTypeExtensionInterface[] An array of extensions as ActionTypeExtensionInterface instances
     */
    public function getActionTypeExtensions(string $name): array;

    /**
     * Returns whether this extension provides type extensions for the given action type.
     *
     * @param string $name The name of the action type
     *
     * @return bool Whether the given type has extensions
     */
    public function hasActionTypeExtensions(string $name): bool;

    /**
     * Returns the adapter factory by name.
     *
     * @param string $name The name of the adapter
     *
     * @return AdapterFactoryInterface The adapter factory
     *
     * @throws InvalidArgumentException If the given adapter is not provided by this extension
     */
    public function getAdapterFactory(string $name): AdapterFactoryInterface;

    /**
     * Returns whether this extension provides the adapter factory for the given name.
     *
     * @param string $name The name of the adapter factory
     *
     * @return bool Whether the adapter is supported by this extension
     */
    public function hasAdapterFactory(string $name): bool;
}

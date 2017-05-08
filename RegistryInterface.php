<?php

namespace Ekyna\Component\Table;

/**
 * Interface TableRegistryInterface
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface RegistryInterface
{
    /**
     * Returns a table type by name.
     *
     * This methods registers the type extensions from the table extensions.
     *
     * @param string $name The name of the type
     *
     * @return ResolvedTableTypeInterface
     *
     * @throws Exception\InvalidArgumentException If the type can not be retrieved from any extension
     */
    public function getTableType($name);

    /**
     * Returns whether the given table type is supported.
     *
     * @param string $name The name of the type
     *
     * @return Boolean Whether the type is supported
     */
    public function hasTableType($name);

    /**
     * Returns a column type by name.
     *
     * This methods registers the type extensions from the table extensions.
     *
     * @param string $name The name of the type
     *
     * @return Column\ResolvedColumnTypeInterface
     *
     * @throws Exception\InvalidArgumentException If the type can not be retrieved from any extension
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
     * This methods registers the type extensions from the table extensions.
     *
     * @param string $name The name of the type
     *
     * @return Filter\ResolvedFilterTypeInterface
     *
     * @throws Exception\InvalidArgumentException If the type can not be retrieved from any extension
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
     * Returns a action type by name.
     *
     * This methods registers the type extensions from the table extensions.
     *
     * @param string $name The name of the type
     *
     * @return Action\ResolvedActionTypeInterface
     *
     * @throws Exception\InvalidArgumentException If the type can not be retrieved from any extension
     */
    public function getActionType($name);

    /**
     * Returns whether the given action type is supported.
     *
     * @param string $name The name of the type
     *
     * @return Boolean Whether the type is supported
     */
    public function hasActionType($name);

    /**
     * Returns the adapter factory by name.
     *
     * @param string $name The name of the adapter factory
     *
     * @return Source\AdapterFactoryInterface
     *
     * @throws Exception\InvalidArgumentException If the adapter factory can not be retrieved from any extension
     */
    public function getAdapterFactory($name);

    /**
     * Returns whether the given adapter factory is supported.
     *
     * @param string $name The name of the adapter
     *
     * @return Boolean Whether the adapter factory is supported
     */
    public function hasAdapterFactory($name);

    /**
     * Returns the extensions loaded by the framework.
     *
     * @return Extension\TableExtensionInterface[]
     */
    public function getExtensions();
}

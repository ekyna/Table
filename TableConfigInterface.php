<?php

namespace Ekyna\Component\Table;

/**
 * Interface TableConfigInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface TableConfigInterface
{
    /**
     * Returns the table hash.
     *
     * @return string
     */
    public function getHash();

    /**
     * Returns the table name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the table type used to construct the table.
     *
     * @return ResolvedTableTypeInterface
     */
    public function getType();

    /**
     * Returns the data source.
     *
     * @return Source\SourceInterface
     */
    public function getSource();

    /**
     * Returns the data class.
     *
     * @return string
     */
    public function getDataClass();

    /**
     * Returns the "max per page" choices.
     *
     * @return array
     */
    public function getPerPageChoices();

    /**
     * Returns the table url.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Returns the default sorts.
     *
     * @return [propertyPath => direction] An array with property path as keys and direction as values.
     */
    public function getDefaultSorts();

    /**
     * Returns whether the table's rows can be sorted.
     *
     * @return bool
     */
    public function isSortable();

    /**
     * Returns whether the table's rows can be filtered.
     *
     * @return bool
     */
    public function isFilterable();

    /**
     * Returns whether the table's batch actions are enabled.
     *
     * @return bool
     */
    public function isBatchable();

    /**
     * Returns whether the table can be exported.
     *
     * @return bool
     */
    public function isExportable();

    /**
     * Returns whether the table can be configured (visible columns / max per page).
     *
     * @return bool
     */
    public function isConfigurable();

    /**
     * Returns whether the table profiles are enabled.
     *
     * @return bool
     */
    public function isProfileable();

    /**
     * Returns the selection mode.
     *
     * @return string
     */
    public function getSelectionMode();

    /**
     * Returns the http adapter used by the table.
     *
     * @return Http\AdapterInterface The http adapter
     */
    public function getHttpAdapter();

    /**
     * Returns the session storage used by the table.
     *
     * @return Context\Session\StorageInterface The session storage
     */
    public function getSessionStorage();

    /**
     * Returns the profile storage used by the table.
     *
     * @return Context\Profile\StorageInterface|null The profile storage or null if profile are not supported
     */
    public function getProfileStorage();

    /**
     * Returns whether some export adapters are configured.
     *
     * @return bool
     */
    public function hasExportAdapters();

    /**
     * Returns the export adapters used by the table.
     *
     * @return Export\AdapterInterface[] The export adapters
     */
    public function getExportAdapters();

    /**
     * Returns all options passed during the construction of the table.
     *
     * @return array The passed options
     */
    public function getOptions();

    /**
     * Returns whether a specific option exists.
     *
     * @param string $name The option name,
     *
     * @return bool Whether the option exists
     */
    public function hasOption($name);

    /**
     * Returns the value of a specific option.
     *
     * @param string $name    The option name
     * @param mixed  $default The value returned if the option does not exist
     *
     * @return mixed The option value
     */
    public function getOption($name, $default = null);

    /**
     * Returns the factory.
     *
     * @return \Ekyna\Component\Table\FactoryInterface
     */
    public function getFactory();
}

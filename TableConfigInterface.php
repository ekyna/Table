<?php

declare(strict_types=1);

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
    public function getHash(): string;

    /**
     * Returns the table name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the table type used to construct the table.
     *
     * @return ResolvedTableTypeInterface
     */
    public function getType(): ResolvedTableTypeInterface;

    /**
     * Returns the data source.
     *
     * @return Source\SourceInterface
     */
    public function getSource(): Source\SourceInterface;

    /**
     * Returns the data class.
     *
     * @return string
     */
    public function getDataClass(): string;

    /**
     * Returns the "max per page" choices.
     *
     * @return array
     */
    public function getPerPageChoices(): array;

    /**
     * Returns the table url.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Returns the default sorts.
     *
     * @return array ([propertyPath => direction]) An array with property path as keys and direction as values.
     */
    public function getDefaultSorts(): array;

    /**
     * Returns whether the table's rows can be sorted.
     *
     * @return bool
     */
    public function isSortable(): bool;

    /**
     * Returns whether the table's rows can be filtered.
     *
     * @return bool
     */
    public function isFilterable(): bool;

    /**
     * Returns whether the table's batch actions are enabled.
     *
     * @return bool
     */
    public function isBatchable(): bool;

    /**
     * Returns whether the table can be exported.
     *
     * @return bool
     */
    public function isExportable(): bool;

    /**
     * Returns whether the table can be configured (visible columns / max per page).
     *
     * @return bool
     */
    public function isConfigurable(): bool;

    /**
     * Returns whether the table profiles are enabled.
     *
     * @return bool
     */
    public function isProfileable(): bool;

    /**
     * Returns the selection mode.
     *
     * @return string|null
     */
    public function getSelectionMode(): ?string;

    /**
     * Returns the http adapter used by the table.
     *
     * @return Http\AdapterInterface The http adapter
     */
    public function getHttpAdapter(): Http\AdapterInterface;

    /**
     * Returns the session storage used by the table.
     *
     * @return Context\Session\StorageInterface The session storage
     */
    public function getSessionStorage(): Context\Session\StorageInterface;

    /**
     * Returns the profile storage used by the table.
     *
     * @return Context\Profile\StorageInterface|null The profile storage or null if profile are not supported
     */
    public function getProfileStorage(): ?Context\Profile\StorageInterface;

    /**
     * Returns whether some export adapters are configured.
     *
     * @return bool
     */
    public function hasExportAdapters(): bool;

    /**
     * Returns the export adapters used by the table.
     *
     * @return Export\AdapterInterface[] The export adapters
     */
    public function getExportAdapters(): array;

    /**
     * Returns all options passed during the construction of the table.
     *
     * @return array The passed options
     */
    public function getOptions(): array;

    /**
     * Returns whether a specific option exists.
     *
     * @param string $name The option name,
     *
     * @return bool Whether the option exists
     */
    public function hasOption(string $name): bool;

    /**
     * Returns the value of a specific option.
     *
     * @param string $name    The option name
     * @param mixed  $default The value returned if the option does not exist
     *
     * @return mixed The option value
     */
    public function getOption(string $name, $default = null);

    /**
     * Returns the factory.
     *
     * @return TableFactoryInterface
     */
    public function getFactory(): TableFactoryInterface;
}

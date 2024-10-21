<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

/**
 * Interface TableConfigBuilderInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface TableConfigBuilderInterface extends TableConfigInterface
{
    /**
     * Sets the table type.
     *
     * @param ResolvedTableTypeInterface $type
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setType(ResolvedTableTypeInterface $type): TableConfigBuilderInterface;

    /**
     * Sets the data source.
     *
     * @param Source\SourceInterface $source
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setSource(Source\SourceInterface $source): TableConfigBuilderInterface;

    /**
     * Sets the data class.
     *
     * @param string $class
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setDataClass(string $class): TableConfigBuilderInterface;

    /**
     * Sets the "max per page" choices.
     *
     * @param array $choices
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setPerPageChoices(array $choices): TableConfigBuilderInterface;

    /**
     * Sets the table url.
     *
     * @param string $url
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setUrl(string $url): TableConfigBuilderInterface;

    /**
     * Adds a default sort.
     *
     * @param string      $propertyPath
     * @param string|null $direction Default to ASC if null
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function addDefaultSort(string $propertyPath, string $direction = null): TableConfigBuilderInterface;

    /**
     * Sets whether the table's rows can be sorted.
     *
     * @param bool $enabled
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setSortable(bool $enabled): TableConfigBuilderInterface;

    /**
     * Sets whether the table's rows can be filtered.
     *
     * @param bool $enabled
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setFilterable(bool $enabled): TableConfigBuilderInterface;

    /**
     * Sets whether the table's batch actions are enabled.
     *
     * @param bool $enabled
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setBatchable(bool $enabled): TableConfigBuilderInterface;

    /**
     * Sets whether the table can be exported.
     *
     * @param bool $enabled
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setExportable(bool $enabled): TableConfigBuilderInterface;

    /**
     * Sets whether the table can be configured (visible columns / max per page).
     *
     * @param bool $enabled
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setConfigurable(bool $enabled): TableConfigBuilderInterface;

    /**
     * Sets whether the table profiles are enabled.
     *
     * @param bool $enabled
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setProfileable(bool $enabled): TableConfigBuilderInterface;

    /**
     * Adds a profile.
     *
     * @param Context\Profile\Profile $profile
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function addProfile(Context\Profile\Profile $profile): TableConfigBuilderInterface;

    /**
     * Sets the selection mode.
     *
     * @param string|null $mode
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setSelectionMode(string $mode = null): TableConfigBuilderInterface;

    /**
     * Sets the http adapter.
     *
     * @param Http\AdapterInterface $adapter
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setHttpAdapter(Http\AdapterInterface $adapter): TableConfigBuilderInterface;

    /**
     * Sets the session storage.
     *
     * @param Context\Session\StorageInterface $storage
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setSessionStorage(Context\Session\StorageInterface $storage): TableConfigBuilderInterface;

    /**
     * Sets the profile storage.
     *
     * @param Context\Profile\StorageInterface $storage
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setProfileStorage(Context\Profile\StorageInterface $storage): TableConfigBuilderInterface;

    /**
     * Adds the export adapter.
     *
     * @param Export\AdapterInterface $adapter
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function addExportAdapter(Export\AdapterInterface $adapter): TableConfigBuilderInterface;

    /**
     * Sets the factory.
     *
     * @param TableFactoryInterface $factory
     *
     * @return $this|TableConfigBuilderInterface
     */
    public function setFactory(TableFactoryInterface $factory): TableConfigBuilderInterface;

    /**
     * Builds and returns the table configuration.
     *
     * @return TableConfigInterface
     */
    public function getTableConfig(): TableConfigInterface;
}

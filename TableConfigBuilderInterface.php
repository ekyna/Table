<?php

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
     * @return $this
     */
    public function setType(ResolvedTableTypeInterface $type);

    /**
     * Sets the data source.
     *
     * @param Source\SourceInterface $source
     *
     * @return $this
     */
    public function setSource(Source\SourceInterface $source);

    /**
     * Sets the data class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function setDataClass($class);

    /**
     * Sets the "max per page" choices.
     *
     * @param array $choices
     *
     * @return $this
     */
    public function setPerPageChoices(array $choices);

    /**
     * Sets the table url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url);

    /**
     * Adds a default sort.
     *
     * @param string $propertyPath
     * @param string $direction Default to ASC if null
     *
     * @return $this
     */
    public function addDefaultSort($propertyPath, $direction = null);

    /**
     * Sets whether the table's rows can be sorted.
     *
     * @param bool $enabled
     *
     * @return $this
     */
    public function setSortable($enabled);

    /**
     * Sets whether the table's rows can be filtered.
     *
     * @param bool $enabled
     *
     * @return $this
     */
    public function setFilterable($enabled);

    /**
     * Sets whether the table's batch actions are enabled.
     *
     * @param bool $enabled
     *
     * @return $this
     */
    public function setBatchable($enabled);

    /**
     * Sets whether the table can be exported.
     *
     * @param bool $enabled
     *
     * @return $this
     */
    public function setExportable($enabled);

    /**
     * Sets whether the table can be configured (visible columns / max per page).
     *
     * @param bool $enabled
     *
     * @return $this
     */
    public function setConfigurable($enabled);

    /**
     * Sets whether the table profiles are enabled.
     *
     * @param bool $enabled
     *
     * @return $this
     */
    public function setProfileable($enabled);

    /**
     * Sets the selection mode.
     *
     * @param string $mode
     *
     * @return TableConfigBuilder
     */
    public function setSelectionMode($mode);

    /**
     * Sets the http adapter.
     *
     * @param Http\AdapterInterface $adapter
     *
     * @return $this
     */
    public function setHttpAdapter(Http\AdapterInterface $adapter);

    /**
     * Sets the session storage.
     *
     * @param Context\Session\StorageInterface $storage
     *
     * @return $this
     */
    public function setSessionStorage(Context\Session\StorageInterface $storage);

    /**
     * Sets the profile storage.
     *
     * @param Context\Profile\StorageInterface $storage
     *
     * @return $this
     */
    public function setProfileStorage(Context\Profile\StorageInterface $storage);

    /**
     * Adds the export adapter.
     *
     * @param Export\AdapterInterface $adapter
     *
     * @return $this
     */
    public function addExportAdapter(Export\AdapterInterface $adapter);

    /**
     * Sets the factory.
     *
     * @param FactoryInterface $factory
     *
     * @return $this
     */
    public function setFactory(FactoryInterface $factory);

    /**
     * Builds and returns the table configuration.
     *
     * @return TableConfigInterface
     */
    public function getTableConfig();
}

<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Util\ColumnSort;
use Symfony\Component\Form\Util\StringUtil;

/**
 * Class TableConfigBuilder
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableConfigBuilder implements TableConfigBuilderInterface
{
    private bool                              $locked         = false;
    private string                            $hash;
    private string                            $name;
    private ResolvedTableTypeInterface        $type;
    private Source\SourceInterface            $source;
    private string                            $dataClass;
    private array                             $perPageChoices;
    private string                            $url;
    private array                             $defaultSorts   = [];
    private bool                              $sortable;
    private bool                              $filterable;
    private bool                              $batchable;
    private bool                              $exportable;
    private bool                              $profileable;
    private bool                              $configurable;
    private ?string                           $selectionMode  = null;
    private Http\AdapterInterface             $httpAdapter;
    private Context\Session\StorageInterface  $sessionStorage;
    private ?Context\Profile\StorageInterface $profileStorage = null;
    /** @var Export\AdapterInterface[] */
    private array                 $exportAdapters = [];
    private TableFactoryInterface $factory;
    private array                 $options;


    /**
     * Constructor.
     *
     * @param string $name
     * @param array  $options
     */
    public function __construct(string $name, array $options = [])
    {
        Util\Config::validateName($name);

        $this->name = $name;
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getType(): ResolvedTableTypeInterface
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getSource(): Source\SourceInterface
    {
        return $this->source;
    }

    /**
     * @inheritDoc
     */
    public function getDataClass(): string
    {
        return $this->dataClass;
    }

    /**
     * @inheritDoc
     */
    public function getPerPageChoices(): array
    {
        return $this->perPageChoices;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultSorts(): array
    {
        return $this->defaultSorts;
    }

    /**
     * @inheritDoc
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @inheritDoc
     */
    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    /**
     * @inheritDoc
     */
    public function isBatchable(): bool
    {
        return $this->batchable;
    }

    /**
     * @inheritDoc
     */
    public function isExportable(): bool
    {
        return $this->exportable;
    }

    /**
     * @inheritDoc
     */
    public function isConfigurable(): bool
    {
        return $this->configurable;
    }

    /**
     * @inheritDoc
     */
    public function isProfileable(): bool
    {
        return $this->profileable;
    }

    /**
     * @inheritDoc
     */
    public function getSelectionMode(): ?string
    {
        return $this->selectionMode;
    }

    /**
     * @inheritDoc
     */
    public function getHttpAdapter(): Http\AdapterInterface
    {
        return $this->httpAdapter;
    }

    /**
     * @inheritDoc
     */
    public function getSessionStorage(): Context\Session\StorageInterface
    {
        return $this->sessionStorage;
    }

    /**
     * @inheritDoc
     */
    public function getProfileStorage(): ?Context\Profile\StorageInterface
    {
        return $this->profileStorage;
    }

    /**
     * @inheritDoc
     */
    public function hasExportAdapters(): bool
    {
        return !empty($this->exportAdapters);
    }

    /**
     * @inheritDoc
     */
    public function getExportAdapters(): array
    {
        return $this->exportAdapters;
    }

    /**
     * @inheritDoc
     */
    public function getFactory(): TableFactoryInterface
    {
        return $this->factory;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @inheritDoc
     */
    public function getOption(string $name, $default = null)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $default;
    }

    /**
     * @inheritDoc
     */
    public function setType(ResolvedTableTypeInterface $type): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->type = $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSource(Source\SourceInterface $source): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->source = $source;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDataClass(string $class): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->dataClass = $class;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl(string $url): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->url = $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPerPageChoices(array $choices): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->perPageChoices = $choices;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addDefaultSort(string $propertyPath, string $direction = null): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        if (null === $direction) {
            $direction = ColumnSort::ASC;
        }

        ColumnSort::isValid($direction, true);

        $this->defaultSorts[$propertyPath] = $direction;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSortable(bool $enabled): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->sortable = $enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFilterable(bool $enabled): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->filterable = $enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBatchable(bool $enabled): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->batchable = $enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setExportable(bool $enabled): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->exportable = $enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setConfigurable(bool $enabled): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->configurable = $enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setProfileable(bool $enabled): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->profileable = $enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSelectionMode(string $mode = null): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->selectionMode = $mode;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setHttpAdapter(Http\AdapterInterface $adapter): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->httpAdapter = $adapter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSessionStorage(Context\Session\StorageInterface $storage): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->sessionStorage = $storage;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setProfileStorage(Context\Profile\StorageInterface $storage): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->profileStorage = $storage;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addExportAdapter(Export\AdapterInterface $adapter): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->exportAdapters[] = $adapter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFactory(TableFactoryInterface $factory): TableConfigBuilderInterface
    {
        $this->preventIfLocked();

        $this->factory = $factory;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTableConfig(): TableConfigInterface
    {
        $this->preventIfLocked();

        // This method should be idempotent, so clone the builder
        $config = clone $this;

        // Generate hash
        $prefix = StringUtil::fqcnToBlockPrefix(get_class($this->type->getInnerType()));
        $config->hash = hash('crc32', $prefix . '_' . $this->name);

        // Lock
        $config->locked = true;

        return $config;
    }

    /**
     * Prevents method call if the config builder is locked.
     *
     * @throws Exception\BadMethodCallException
     */
    protected function preventIfLocked(): void
    {
        if (!$this->locked) {
            return;
        }

        throw new Exception\BadMethodCallException('The config builder cannot be modified anymore.');
    }
}

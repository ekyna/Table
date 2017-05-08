<?php

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
    /**
     * @var bool
     */
    private $locked;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ResolvedTableTypeInterface
     */
    private $type;

    /**
     * @var Source\SourceInterface
     */
    private $source;

    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var array
     */
    private $perPageChoices;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $defaultSorts = [];

    /**
     * @var bool
     */
    private $sortable;

    /**
     * @var bool
     */
    private $filterable;

    /**
     * @var bool
     */
    private $batchable;

    /**
     * @var bool
     */
    private $exportable;

    /**
     * @var bool
     */
    private $profileable;

    /**
     * @var bool
     */
    private $configurable;

    /**
     * @var string
     */
    private $selectionMode;

    /**
     * @var Http\AdapterInterface
     */
    private $httpAdapter;

    /**
     * @var Context\Session\StorageInterface
     */
    private $sessionStorage;

    /**
     * @var Context\Profile\StorageInterface
     */
    private $profileStorage;

    /**
     * @var Export\AdapterInterface
     */
    private $exportAdapters = [];

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var array
     */
    private $options;


    /**
     * Constructor.
     *
     * @param string $name
     * @param array  $options
     */
    public function __construct($name, array $options = [])
    {
        Util\Config::validateName($name);

        $this->name = (string)$name;
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @inheritDoc
     */
    public function getDataClass()
    {
        return $this->dataClass;
    }

    /**
     * @inheritDoc
     */
    public function getPerPageChoices()
    {
        return $this->perPageChoices;
    }

    /**
     * @inheritDoc
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultSorts()
    {
        return $this->defaultSorts;
    }

    /**
     * @inheritDoc
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * @inheritDoc
     */
    public function isFilterable()
    {
        return $this->filterable;
    }

    /**
     * @inheritDoc
     */
    public function isBatchable()
    {
        return $this->batchable;
    }

    /**
     * @inheritDoc
     */
    public function isExportable()
    {
        return $this->exportable;
    }

    /**
     * @inheritDoc
     */
    public function isConfigurable()
    {
        return $this->configurable;
    }

    /**
     * @inheritDoc
     */
    public function isProfileable()
    {
        return $this->profileable;
    }

    /**
     * @inheritDoc
     */
    public function getSelectionMode()
    {
        return $this->selectionMode;
    }

    /**
     * @inheritDoc
     */
    public function getHttpAdapter()
    {
        return $this->httpAdapter;
    }

    /**
     * @inheritDoc
     */
    public function getSessionStorage()
    {
        return $this->sessionStorage;
    }

    /**
     * @inheritDoc
     */
    public function getProfileStorage()
    {
        return $this->profileStorage;
    }

    /**
     * @inheritDoc
     */
    public function hasExportAdapters()
    {
        return !empty($this->exportAdapters);
    }

    /**
     * @inheritDoc
     */
    public function getExportAdapters()
    {
        return $this->exportAdapters;
    }

    /**
     * @inheritDoc
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @inheritDoc
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @inheritDoc
     */
    public function getOption($name, $default = null)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $default;
    }

    /**
     * @inheritDoc
     */
    public function setType(ResolvedTableTypeInterface $type)
    {
        $this->preventIfLocked();

        $this->type = $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSource(Source\SourceInterface $source)
    {
        $this->preventIfLocked();

        $this->source = $source;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDataClass($class)
    {
        $this->preventIfLocked();

        $this->dataClass = $class;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl($url)
    {
        $this->preventIfLocked();

        $this->url = $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPerPageChoices(array $choices)
    {
        $this->preventIfLocked();

        $this->perPageChoices = $choices;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addDefaultSort($propertyPath, $direction = null)
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
    public function setSortable($enabled)
    {
        $this->preventIfLocked();

        $this->sortable = (bool)$enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFilterable($enabled)
    {
        $this->preventIfLocked();

        $this->filterable = (bool)$enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBatchable($enabled)
    {
        $this->preventIfLocked();

        $this->batchable = (bool)$enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setExportable($enabled)
    {
        $this->preventIfLocked();

        $this->exportable = (bool)$enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setConfigurable($enabled)
    {
        $this->preventIfLocked();

        $this->configurable = (bool)$enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setProfileable($enabled)
    {
        $this->preventIfLocked();

        $this->profileable = (bool)$enabled;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSelectionMode($mode)
    {
        $this->preventIfLocked();

        $this->selectionMode = $mode;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setHttpAdapter(Http\AdapterInterface $adapter)
    {
        $this->preventIfLocked();

        $this->httpAdapter = $adapter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSessionStorage(Context\Session\StorageInterface $storage)
    {
        $this->preventIfLocked();

        $this->sessionStorage = $storage;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setProfileStorage(Context\Profile\StorageInterface $storage)
    {
        $this->preventIfLocked();

        $this->profileStorage = $storage;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addExportAdapter(Export\AdapterInterface $adapter)
    {
        $this->preventIfLocked();

        $this->exportAdapters[] = $adapter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->preventIfLocked();

        $this->factory = $factory;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTableConfig()
    {
        $this->preventIfLocked();

        // This method should be idempotent, so clone the builder
        $config = clone $this;

        // Generate hash
        $prefix = StringUtil::fqcnToBlockPrefix(get_class($this->type->getInnerType()));
        $config->hash = crc32($prefix . '_' . $this->name);

        // Lock
        $config->locked = true;

        return $config;
    }

    /**
     * Prevents method call if the config builder is locked.
     *
     * @throws Exception\BadMethodCallException
     */
    protected function preventIfLocked()
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('The config builder cannot be modified anymore.');
        }
    }
}

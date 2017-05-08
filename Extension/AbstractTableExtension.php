<?php

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\Action\ActionTypeInterface;
use Ekyna\Component\Table\Source\AdapterFactoryInterface;
use Ekyna\Component\Table\Column\ColumnTypeInterface;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Filter\FilterTypeInterface;
use Ekyna\Component\Table\TableTypeInterface;

/**
 * Class AbstractTableExtension
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractTableExtension implements TableExtensionInterface
{
    /**
     * The table types provided by this extension
     *
     * @var TableTypeInterface[] An array of TableTypeInterface
     */
    private $tableTypes;

    /**
     * The table type extensions provided by this extension.
     *
     * @var []TableTypeExtensionInterface[] An array of TableTypeExtensionInterface
     */
    private $tableTypeExtensions;

    /**
     * The column types provided by this extension
     *
     * @var ColumnTypeInterface[] An array of ColumnTypeInterface
     */
    private $columnTypes;

    /**
     * The column type extensions provided by this extension.
     *
     * @var []ColumnTypeExtensionInterface[] An array of ColumnTypeExtensionInterface
     */
    private $columnTypeExtensions;

    /**
     * The filter types provided by this extension
     *
     * @var FilterTypeInterface[] An array of FilterTypeInterface
     */
    private $filterTypes;

    /**
     * The filter type extensions provided by this extension.
     *
     * @var []FilterTypeExtensionInterface[] An array of FilterTypeExtensionInterface
     */
    private $filterTypeExtensions;

    /**
     * The action types provided by this extension
     *
     * @var ActionTypeInterface[] An array of ActionTypeInterface
     */
    private $actionTypes;

    /**
     * The action type extensions provided by this extension.
     *
     * @var []ActionTypeExtensionInterface[] An array of ActionTypeExtensionInterface
     */
    private $actionTypeExtensions;

    /**
     * The adapter factories provided by this extension.
     *
     * @var \Ekyna\Component\Table\Source\AdapterFactoryInterface[]
     */
    private $adapterFactories;


    /**
     * @inheritdoc
     */
    public function getTableType($name)
    {
        if (null === $this->tableTypes) {
            $this->initTableTypes();
        }

        if (!isset($this->tableTypes[$name])) {
            throw new InvalidArgumentException(sprintf(
                'The table type "%s" can not be loaded by this extension',
                $name
            ));
        }

        return $this->tableTypes[$name];
    }

    /**
     * @inheritdoc
     */
    public function hasTableType($name)
    {
        if (null === $this->tableTypes) {
            $this->initTableTypes();
        }

        return isset($this->tableTypes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getTableTypeExtensions($name)
    {
        if (null === $this->tableTypeExtensions) {
            $this->initTableTypeExtensions();
        }

        return isset($this->tableTypeExtensions[$name])
            ? $this->tableTypeExtensions[$name]
            : [];
    }

    /**
     * {@inheritdoc}
     */
    public function hasTableTypeExtensions($name)
    {
        if (null === $this->tableTypeExtensions) {
            $this->initTableTypeExtensions();
        }

        return isset($this->tableTypeExtensions[$name]) && count($this->tableTypeExtensions[$name]) > 0;
    }

    /**
     * @inheritdoc
     */
    public function getColumnType($name)
    {
        if (!$this->hasColumnType($name)) {
            throw new InvalidArgumentException(sprintf(
                'The column type "%s" can not be loaded by this extension',
                $name
            ));
        }

        return $this->columnTypes[$name];
    }

    /**
     * @inheritdoc
     */
    public function hasColumnType($name)
    {
        if (null === $this->columnTypes) {
            $this->initColumnTypes();
        }

        return isset($this->columnTypes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnTypeExtensions($name)
    {
        if (null === $this->columnTypeExtensions) {
            $this->initColumnTypeExtensions();
        }

        return isset($this->columnTypeExtensions[$name])
            ? $this->columnTypeExtensions[$name]
            : [];
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumnTypeExtensions($name)
    {
        if (null === $this->columnTypeExtensions) {
            $this->initColumnTypeExtensions();
        }

        return isset($this->columnTypeExtensions[$name]) && count($this->columnTypeExtensions[$name]) > 0;
    }

    /**
     * @inheritdoc
     */
    public function getFilterType($name)
    {
        if (!$this->hasFilterType($name)) {
            throw new InvalidArgumentException(sprintf(
                'The filter type "%s" can not be loaded by this extension',
                $name
            ));
        }

        return $this->filterTypes[$name];
    }

    /**
     * @inheritdoc
     */
    public function hasFilterType($name)
    {
        if (null === $this->filterTypes) {
            $this->initFilterTypes();
        }

        return isset($this->filterTypes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterTypeExtensions($name)
    {
        if (null === $this->filterTypeExtensions) {
            $this->initFilterTypeExtensions();
        }

        return isset($this->filterTypeExtensions[$name])
            ? $this->filterTypeExtensions[$name]
            : [];
    }

    /**
     * {@inheritdoc}
     */
    public function hasFilterTypeExtensions($name)
    {
        if (null === $this->filterTypeExtensions) {
            $this->initFilterTypeExtensions();
        }

        return isset($this->filterTypeExtensions[$name]) && count($this->filterTypeExtensions[$name]) > 0;
    }

    /**
     * @inheritdoc
     */
    public function getActionType($name)
    {
        if (!$this->hasActionType($name)) {
            throw new InvalidArgumentException(sprintf(
                'The action type "%s" can not be loaded by this extension',
                $name
            ));
        }

        return $this->actionTypes[$name];
    }

    /**
     * @inheritdoc
     */
    public function hasActionType($name)
    {
        if (null === $this->actionTypes) {
            $this->initActionTypes();
        }

        return isset($this->actionTypes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getActionTypeExtensions($name)
    {
        if (null === $this->actionTypeExtensions) {
            $this->initActionTypeExtensions();
        }

        return isset($this->actionTypeExtensions[$name])
            ? $this->actionTypeExtensions[$name]
            : [];
    }

    /**
     * {@inheritdoc}
     */
    public function hasActionTypeExtensions($name)
    {
        if (null === $this->actionTypeExtensions) {
            $this->initActionTypeExtensions();
        }

        return isset($this->actionTypeExtensions[$name]) && count($this->actionTypeExtensions[$name]) > 0;
    }

    /**
     * @inheritdoc
     */
    public function getAdapterFactory($name)
    {
        if (!$this->hasAdapterFactory($name)) {
            throw new InvalidArgumentException(sprintf(
                'The adapter factory "%s" can not be loaded by this extension',
                $name
            ));
        }

        return $this->adapterFactories[$name];
    }

    /**
     * @inheritdoc
     */
    public function hasAdapterFactory($name)
    {
        if (null === $this->adapterFactories) {
            $this->initAdapterFactories();
        }

        return isset($this->adapterFactories[$name]);
    }

    /**
     * Returns the adapters.
     *
     * @return \Ekyna\Component\Table\Source\AdapterInterface[] The adapters
     */
    public function getAdapterFactories()
    {
        return [];
    }

    /**
     * Registers the table types.
     *
     * @return TableTypeInterface[] An array of TableTypeInterface instances
     */
    protected function loadTableTypes()
    {
        return [];
    }

    /**
     * Registers the table type extensions.
     *
     * @return TableTypeExtensionInterface[] An array of TableTypeExtensionInterface instances
     */
    protected function loadTableTypeExtensions()
    {
        return [];
    }

    /**
     * Registers the column types.
     *
     * @return ColumnTypeInterface[] An array of ColumnTypeInterface instances
     */
    protected function loadColumnTypes()
    {
        return [];
    }

    /**
     * Registers the column type extensions.
     *
     * @return ColumnTypeExtensionInterface[] An array of ColumnTypeExtensionInterface instances
     */
    protected function loadColumnTypeExtensions()
    {
        return [];
    }

    /**
     * Registers the filter types.
     *
     * @return \Ekyna\Component\Table\Filter\FilterTypeInterface[] An array of FilterTypeInterface instances
     */
    protected function loadFilterTypes()
    {
        return [];
    }

    /**
     * Registers the filter type extensions.
     *
     * @return FilterTypeExtensionInterface[] An array of FilterTypeExtensionInterface instances
     */
    protected function loadFilterTypeExtensions()
    {
        return [];
    }

    /**
     * Registers the action types.
     *
     * @return \Ekyna\Component\Table\Action\ActionTypeInterface[] An array of ActionTypeInterface instances
     */
    protected function loadActionTypes()
    {
        return [];
    }

    /**
     * Registers the action type extensions.
     *
     * @return ActionTypeExtensionInterface[] An array of ActionTypeExtensionInterface instances
     */
    protected function loadActionTypeExtensions()
    {
        return [];
    }

    /**
     * Registers the table adapter factories.
     *
     * @return AdapterFactoryInterface[] An array of AdapterFactoryInterface instances
     */
    protected function loadAdapterFactories()
    {
        return [];
    }

    /**
     * Initializes the table types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of TableTypeInterface
     */
    private function initTableTypes()
    {
        $this->tableTypes = [];

        foreach ($this->loadTableTypes() as $type) {
            if (!$type instanceof TableTypeInterface) {
                throw new UnexpectedTypeException($type, TableTypeInterface::class);
            }

            $this->tableTypes[get_class($type)] = $type;
        }
    }

    /**
     * Initializes the table type extensions.
     *
     * @throws UnexpectedTypeException if any registered type extension is not
     *                                 an instance of FormTypeExtensionInterface
     */
    private function initTableTypeExtensions()
    {
        $this->tableTypeExtensions = [];

        foreach ($this->loadTableTypeExtensions() as $extension) {
            if (!$extension instanceof TableTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, TableTypeExtensionInterface::class);
            }

            $type = $extension->getExtendedType();

            $this->tableTypeExtensions[$type][] = $extension;
        }
    }

    /**
     * Initializes the column types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of ColumnTypeInterface
     */
    private function initColumnTypes()
    {
        $this->columnTypes = [];

        foreach ($this->loadColumnTypes() as $type) {
            if (!$type instanceof ColumnTypeInterface) {
                throw new UnexpectedTypeException($type, ColumnTypeInterface::class);
            }

            $this->columnTypes[get_class($type)] = $type;
        }
    }

    /**
     * Initializes the column type extensions.
     *
     * @throws UnexpectedTypeException if any registered type extension is not
     *                                 an instance of FormTypeExtensionInterface
     */
    private function initColumnTypeExtensions()
    {
        $this->columnTypeExtensions = [];

        foreach ($this->loadColumnTypeExtensions() as $extension) {
            if (!$extension instanceof ColumnTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, ColumnTypeExtensionInterface::class);
            }

            $type = $extension->getExtendedType();

            $this->columnTypeExtensions[$type][] = $extension;
        }
    }

    /**
     * Initializes the filter types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of FilterTypeInterface
     */
    private function initFilterTypes()
    {
        $this->filterTypes = [];

        foreach ($this->loadFilterTypes() as $type) {
            if (!$type instanceof FilterTypeInterface) {
                throw new UnexpectedTypeException($type, FilterTypeInterface::class);
            }

            $this->filterTypes[get_class($type)] = $type;
        }
    }

    /**
     * Initializes the filter type extensions.
     *
     * @throws UnexpectedTypeException if any registered type extension is not
     *                                 an instance of FormTypeExtensionInterface
     */
    private function initFilterTypeExtensions()
    {
        $this->filterTypeExtensions = [];

        foreach ($this->loadFilterTypeExtensions() as $extension) {
            if (!$extension instanceof FilterTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, FilterTypeExtensionInterface::class);
            }

            $type = $extension->getExtendedType();

            $this->filterTypeExtensions[$type][] = $extension;
        }
    }

    /**
     * Initializes the action types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of ActionTypeInterface
     */
    private function initActionTypes()
    {
        $this->actionTypes = [];

        foreach ($this->loadActionTypes() as $type) {
            if (!$type instanceof ActionTypeInterface) {
                throw new UnexpectedTypeException($type, ActionTypeInterface::class);
            }

            $this->actionTypes[get_class($type)] = $type;
        }
    }

    /**
     * Initializes the action type extensions.
     *
     * @throws UnexpectedTypeException if any registered type extension is not
     *                                 an instance of FormTypeExtensionInterface
     */
    private function initActionTypeExtensions()
    {
        $this->actionTypeExtensions = [];

        foreach ($this->loadActionTypeExtensions() as $extension) {
            if (!$extension instanceof ActionTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, ActionTypeExtensionInterface::class);
            }

            $type = $extension->getExtendedType();

            $this->actionTypeExtensions[$type][] = $extension;
        }
    }

    /**
     * Initializes the adapter factories.
     *
     * @throws UnexpectedTypeException if any registered adapter factory is not an instance of AdapterFactoryInterface
     */
    private function initAdapterFactories()
    {
        $this->adapterFactories = [];

        foreach ($this->loadAdapterFactories() as $adapter) {
            if (!$adapter instanceof AdapterFactoryInterface) {
                throw new UnexpectedTypeException($adapter, AdapterFactoryInterface::class);
            }

            $this->adapterFactories[get_class($adapter)] = $adapter;
        }
    }
}

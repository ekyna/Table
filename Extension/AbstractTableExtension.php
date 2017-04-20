<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\Action\ActionTypeInterface;
use Ekyna\Component\Table\Source\AdapterFactoryInterface;
use Ekyna\Component\Table\Column\ColumnTypeInterface;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Filter\FilterTypeInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
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
     * @var TableTypeInterface[]|null An array of TableTypeInterface
     */
    private ?array $tableTypes = null;

    /**
     * The table type extensions provided by this extension.
     *
     * @var array|null An array of {@link TableTypeExtensionInterface} arrays
     */
    private ?array $tableTypeExtensions = null;

    /**
     * The column types provided by this extension
     *
     * @var ColumnTypeInterface[]|null An array of ColumnTypeInterface
     */
    private ?array $columnTypes = null;

    /**
     * The column type extensions provided by this extension.
     *
     * @var array|null An array of {@link ColumnTypeExtensionInterface} arrays
     */
    private ?array $columnTypeExtensions = null;

    /**
     * The filter types provided by this extension
     *
     * @var FilterTypeInterface[]|null An array of FilterTypeInterface
     */
    private ?array $filterTypes = null;

    /**
     * The filter type extensions provided by this extension.
     *
     * @var array|null An array of {@link FilterTypeExtensionInterface} arrays
     */
    private ?array $filterTypeExtensions = null;

    /**
     * The action types provided by this extension
     *
     * @var ActionTypeInterface[]|null An array of ActionTypeInterface arrays.
     */
    private ?array $actionTypes = null;

    /**
     * The action type extensions provided by this extension.
     *
     * @var array|null An array of {@link ActionTypeExtensionInterface} arrays
     */
    private ?array $actionTypeExtensions = null;

    /**
     * The adapter factories provided by this extension.
     *
     * @var AdapterFactoryInterface[]|null
     */
    private ?array $adapterFactories = null;


    /**
     * @inheritDoc
     */
    public function getTableType(string $name): TableTypeInterface
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
     * @inheritDoc
     */
    public function hasTableType(string $name): bool
    {
        if (null === $this->tableTypes) {
            $this->initTableTypes();
        }

        return isset($this->tableTypes[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getTableTypeExtensions(string $name): array
    {
        if (null === $this->tableTypeExtensions) {
            $this->initTableTypeExtensions();
        }

        return isset($this->tableTypeExtensions[$name])
            ? $this->tableTypeExtensions[$name]
            : [];
    }

    /**
     * @inheritDoc
     */
    public function hasTableTypeExtensions(string $name): bool
    {
        if (null === $this->tableTypeExtensions) {
            $this->initTableTypeExtensions();
        }

        return isset($this->tableTypeExtensions[$name]) && count($this->tableTypeExtensions[$name]) > 0;
    }

    /**
     * @inheritDoc
     */
    public function getColumnType(string $name): ColumnTypeInterface
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
     * @inheritDoc
     */
    public function hasColumnType(string $name): bool
    {
        if (null === $this->columnTypes) {
            $this->initColumnTypes();
        }

        return isset($this->columnTypes[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getColumnTypeExtensions(string $name): array
    {
        if (null === $this->columnTypeExtensions) {
            $this->initColumnTypeExtensions();
        }

        return isset($this->columnTypeExtensions[$name])
            ? $this->columnTypeExtensions[$name]
            : [];
    }

    /**
     * @inheritDoc
     */
    public function hasColumnTypeExtensions(string $name): bool
    {
        if (null === $this->columnTypeExtensions) {
            $this->initColumnTypeExtensions();
        }

        return isset($this->columnTypeExtensions[$name]) && count($this->columnTypeExtensions[$name]) > 0;
    }

    /**
     * @inheritDoc
     */
    public function getFilterType(string $name): FilterTypeInterface
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
     * @inheritDoc
     */
    public function hasFilterType(string $name): bool
    {
        if (null === $this->filterTypes) {
            $this->initFilterTypes();
        }

        return isset($this->filterTypes[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getFilterTypeExtensions(string $name): array
    {
        if (null === $this->filterTypeExtensions) {
            $this->initFilterTypeExtensions();
        }

        return isset($this->filterTypeExtensions[$name])
            ? $this->filterTypeExtensions[$name]
            : [];
    }

    /**
     * @inheritDoc
     */
    public function hasFilterTypeExtensions(string $name): bool
    {
        if (null === $this->filterTypeExtensions) {
            $this->initFilterTypeExtensions();
        }

        return isset($this->filterTypeExtensions[$name]) && count($this->filterTypeExtensions[$name]) > 0;
    }

    /**
     * @inheritDoc
     */
    public function getActionType(string $name): ActionTypeInterface
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
     * @inheritDoc
     */
    public function hasActionType(string $name): bool
    {
        if (null === $this->actionTypes) {
            $this->initActionTypes();
        }

        return isset($this->actionTypes[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getActionTypeExtensions(string $name): array
    {
        if (null === $this->actionTypeExtensions) {
            $this->initActionTypeExtensions();
        }

        return isset($this->actionTypeExtensions[$name])
            ? $this->actionTypeExtensions[$name]
            : [];
    }

    /**
     * @inheritDoc
     */
    public function hasActionTypeExtensions(string $name): bool
    {
        if (null === $this->actionTypeExtensions) {
            $this->initActionTypeExtensions();
        }

        return isset($this->actionTypeExtensions[$name]) && count($this->actionTypeExtensions[$name]) > 0;
    }

    /**
     * @inheritDoc
     */
    public function getAdapterFactory(string $name): AdapterFactoryInterface
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
     * @inheritDoc
     */
    public function hasAdapterFactory(string $name): bool
    {
        if (null === $this->adapterFactories) {
            $this->initAdapterFactories();
        }

        return isset($this->adapterFactories[$name]);
    }

    /**
     * Returns the adapters.
     *
     * @return AdapterInterface[] The adapters
     */
    public function getAdapterFactories(): array
    {
        return [];
    }

    /**
     * Registers the table types.
     *
     * @return TableTypeInterface[] An array of TableTypeInterface instances
     */
    protected function loadTableTypes(): array
    {
        return [];
    }

    /**
     * Registers the table type extensions.
     *
     * @return TableTypeExtensionInterface[] An array of TableTypeExtensionInterface instances
     */
    protected function loadTableTypeExtensions(): array
    {
        return [];
    }

    /**
     * Registers the column types.
     *
     * @return ColumnTypeInterface[] An array of ColumnTypeInterface instances
     */
    protected function loadColumnTypes(): array
    {
        return [];
    }

    /**
     * Registers the column type extensions.
     *
     * @return ColumnTypeExtensionInterface[] An array of ColumnTypeExtensionInterface instances
     */
    protected function loadColumnTypeExtensions(): array
    {
        return [];
    }

    /**
     * Registers the filter types.
     *
     * @return FilterTypeInterface[] An array of FilterTypeInterface instances
     */
    protected function loadFilterTypes(): array
    {
        return [];
    }

    /**
     * Registers the filter type extensions.
     *
     * @return FilterTypeExtensionInterface[] An array of FilterTypeExtensionInterface instances
     */
    protected function loadFilterTypeExtensions(): array
    {
        return [];
    }

    /**
     * Registers the action types.
     *
     * @return ActionTypeInterface[] An array of ActionTypeInterface instances
     */
    protected function loadActionTypes(): array
    {
        return [];
    }

    /**
     * Registers the action type extensions.
     *
     * @return ActionTypeExtensionInterface[] An array of ActionTypeExtensionInterface instances
     */
    protected function loadActionTypeExtensions(): array
    {
        return [];
    }

    /**
     * Registers the table adapter factories.
     *
     * @return AdapterFactoryInterface[] An array of AdapterFactoryInterface instances
     */
    protected function loadAdapterFactories(): array
    {
        return [];
    }

    /**
     * Initializes the table types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of TableTypeInterface
     */
    private function initTableTypes(): void
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
    private function initTableTypeExtensions(): void
    {
        $this->tableTypeExtensions = [];

        foreach ($this->loadTableTypeExtensions() as $extension) {
            if (!$extension instanceof TableTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, TableTypeExtensionInterface::class);
            }

            $types = $extension::getExtendedTypes();

            foreach ($types as $type) {
                if (!isset($this->tableTypeExtensions[$type])) {
                    $this->tableTypeExtensions[$type] = [];
                }

                $this->tableTypeExtensions[$type][] = $extension;
            }
        }
    }

    /**
     * Initializes the column types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of ColumnTypeInterface
     */
    private function initColumnTypes(): void
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
    private function initColumnTypeExtensions(): void
    {
        $this->columnTypeExtensions = [];

        foreach ($this->loadColumnTypeExtensions() as $extension) {
            if (!$extension instanceof ColumnTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, ColumnTypeExtensionInterface::class);
            }

            $types = $extension::getExtendedTypes();

            foreach ($types as $type) {
                if (!isset($this->columnTypeExtensions[$type])) {
                    $this->columnTypeExtensions[$type] = [];
                }

                $this->columnTypeExtensions[$type][] = $extension;
            }
        }
    }

    /**
     * Initializes the filter types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of FilterTypeInterface
     */
    private function initFilterTypes(): void
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
    private function initFilterTypeExtensions(): void
    {
        $this->filterTypeExtensions = [];

        foreach ($this->loadFilterTypeExtensions() as $extension) {
            if (!$extension instanceof FilterTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, FilterTypeExtensionInterface::class);
            }

            $types = $extension::getExtendedTypes();

            foreach ($types as $type) {
                if (!isset($this->filterTypeExtensions[$type])) {
                    $this->filterTypeExtensions[$type] = [];
                }

                $this->filterTypeExtensions[$type][] = $extension;
            }
        }
    }

    /**
     * Initializes the action types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of ActionTypeInterface
     */
    private function initActionTypes(): void
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
    private function initActionTypeExtensions(): void
    {
        $this->actionTypeExtensions = [];

        foreach ($this->loadActionTypeExtensions() as $extension) {
            if (!$extension instanceof ActionTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, ActionTypeExtensionInterface::class);
            }

            $types = $extension::getExtendedTypes();

            foreach ($types as $type) {
                if (!isset($this->actionTypeExtensions[$type])) {
                    $this->actionTypeExtensions[$type] = [];
                }

                $this->actionTypeExtensions[$type][] = $extension;
            }
        }
    }

    /**
     * Initializes the adapter factories.
     *
     * @throws UnexpectedTypeException if any registered adapter factory is not an instance of AdapterFactoryInterface
     */
    private function initAdapterFactories(): void
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

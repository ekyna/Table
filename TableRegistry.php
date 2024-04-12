<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Extension\Core\CoreExtension;

use function array_merge;
use function class_exists;
use function get_class;
use function is_subclass_of;

/**
 * Class TableRegistry
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TableRegistry implements RegistryInterface
{
    /** @var Extension\TableExtensionInterface[] */
    private array                        $extensions;
    private ResolvedTypeFactoryInterface $resolvedTypeFactory;

    /** @var ResolvedTableTypeInterface[] */
    private array $tableTypes = [];
    /** @var Column\ResolvedColumnTypeInterface[] */
    private array $columnTypes = [];
    /** @var Filter\ResolvedFilterTypeInterface[] */
    private array $filterTypes = [];
    /** @var Action\ResolvedActionTypeInterface[] */
    private array $actionTypes = [];
    /** @var Source\AdapterFactoryInterface[] */
    private array $adapterFactories = [];


    /**
     * Constructor
     *
     * @param Extension\TableExtensionInterface[] $extensions          An array of TableExtensionInterface
     * @param ResolvedTypeFactoryInterface        $resolvedTypeFactory The factory for resolved types
     */
    public function __construct(array $extensions, ResolvedTypeFactoryInterface $resolvedTypeFactory)
    {
        $this->extensions = [new CoreExtension()];

        foreach ($extensions as $extension) {
            if (!$extension instanceof Extension\TableExtensionInterface) {
                throw new Exception\UnexpectedTypeException($extension, Extension\TableExtensionInterface::class);
            }

            $this->extensions[] = $extension;
        }

        $this->resolvedTypeFactory = $resolvedTypeFactory;
    }

    /**
     * @inheritDoc
     */
    public function getTableType(string $name): ResolvedTableTypeInterface
    {
        if (!isset($this->tableTypes[$name])) {
            $type = null;

            foreach ($this->extensions as $extension) {
                if ($extension->hasTableType($name)) {
                    $type = $extension->getTableType($name);
                    break;
                }
            }

            if (!$type) {
                // Support fully-qualified class names
                if (class_exists($name) && is_subclass_of($name, TableTypeInterface::class)) {
                    $type = new $name();
                } else {
                    throw new Exception\InvalidArgumentException(sprintf('Could not load type "%s"', $name));
                }
            }

            $this->tableTypes[$name] = $this->resolveTableType($type);
        }

        return $this->tableTypes[$name];
    }

    /**
     * Wraps a type into a ResolvedTableTypeInterface implementation
     * and connects it with its parent type.
     *
     * @param TableTypeInterface $type The type to resolve
     *
     * @return ResolvedTableTypeInterface The resolved type
     */
    private function resolveTableType(TableTypeInterface $type): ResolvedTableTypeInterface
    {
        $typeExtensions = [];
        $parentType = $type->getParent();
        $class = get_class($type);

        foreach ($this->extensions as $extension) {
            $typeExtensions = array_merge(
                $typeExtensions,
                $extension->getTableTypeExtensions($class)
            );
        }

        return $this->resolvedTypeFactory->createResolvedTableType(
            $type,
            $typeExtensions,
            $parentType ? $this->getTableType($parentType) : null
        );
    }

    /**
     * @inheritDoc
     */
    public function hasTableType(string $name): bool
    {
        if (isset($this->tableTypes[$name])) {
            return true;
        }

        try {
            $this->getTableType($name);
        } catch (Exception\ExceptionInterface) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getColumnType(string $name): Column\ResolvedColumnTypeInterface
    {
        if (!isset($this->columnTypes[$name])) {
            $type = null;

            foreach ($this->extensions as $extension) {
                if ($extension->hasColumnType($name)) {
                    $type = $extension->getColumnType($name);
                    break;
                }
            }

            if (!$type) {
                // Support fully-qualified class names
                if (class_exists($name) && is_subclass_of($name, Column\ColumnTypeInterface::class)) {
                    $type = new $name();
                } else {
                    throw new Exception\InvalidArgumentException(sprintf('Could not load type "%s"', $name));
                }
            }

            $this->columnTypes[$name] = $this->resolveColumnType($type);
        }

        return $this->columnTypes[$name];
    }

    /**
     * Wraps a type into a ResolvedColumnTypeInterface implementation
     * and connects it with its parent type.
     *
     * @param Column\ColumnTypeInterface $type The type to resolve
     *
     * @return Column\ResolvedColumnTypeInterface The resolved type
     */
    private function resolveColumnType(Column\ColumnTypeInterface $type): Column\ResolvedColumnTypeInterface
    {
        $typeExtensions = [];
        $parentType = $type->getParent();
        $class = get_class($type);

        foreach ($this->extensions as $extension) {
            $typeExtensions = array_merge(
                $typeExtensions,
                $extension->getColumnTypeExtensions($class)
            );
        }

        return $this->resolvedTypeFactory->createResolvedColumnType(
            $type,
            $typeExtensions,
            $parentType ? $this->getColumnType($parentType) : null
        );
    }

    /**
     * @inheritDoc
     */
    public function hasColumnType(string $name): bool
    {
        if (isset($this->columnTypes[$name])) {
            return true;
        }

        try {
            $this->getColumnType($name);
        } catch (Exception\ExceptionInterface) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getFilterType(string $name): Filter\ResolvedFilterTypeInterface
    {
        if (!isset($this->filterTypes[$name])) {
            $type = null;

            foreach ($this->extensions as $extension) {
                if ($extension->hasFilterType($name)) {
                    $type = $extension->getFilterType($name);
                    break;
                }
            }

            if (!$type) {
                // Support fully-qualified class names
                if (class_exists($name) && is_subclass_of($name, Filter\FilterTypeInterface::class)) {
                    $type = new $name();
                } else {
                    throw new Exception\InvalidArgumentException(sprintf('Could not load type "%s"', $name));
                }
            }

            $this->filterTypes[$name] = $this->resolveFilterType($type);
        }

        return $this->filterTypes[$name];
    }

    /**
     * Wraps a type into a ResolvedFilterTypeInterface implementation
     * and connects it with its parent type.
     *
     * @param Filter\FilterTypeInterface $type The type to resolve
     *
     * @return Filter\ResolvedFilterTypeInterface The resolved type
     */
    private function resolveFilterType(Filter\FilterTypeInterface $type): Filter\ResolvedFilterTypeInterface
    {
        $typeExtensions = [];
        $parentType = $type->getParent();
        $class = get_class($type);

        foreach ($this->extensions as $extension) {
            $typeExtensions = array_merge(
                $typeExtensions,
                $extension->getFilterTypeExtensions($class)
            );
        }

        return $this->resolvedTypeFactory->createResolvedFilterType(
            $type,
            $typeExtensions,
            $parentType ? $this->getFilterType($parentType) : null
        );
    }

    /**
     * @inheritDoc
     */
    public function hasFilterType(string $name): bool
    {
        if (isset($this->filterTypes[$name])) {
            return true;
        }

        try {
            $this->getFilterType($name);
        } catch (Exception\ExceptionInterface) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getActionType(string $name): Action\ResolvedActionTypeInterface
    {
        if (!isset($this->actionTypes[$name])) {
            $type = null;

            foreach ($this->extensions as $extension) {
                if ($extension->hasActionType($name)) {
                    $type = $extension->getActionType($name);
                    break;
                }
            }

            if (!$type) {
                // Support fully-qualified class names
                if (class_exists($name) && is_subclass_of($name, Action\ActionTypeInterface::class)) {
                    $type = new $name();
                } else {
                    throw new Exception\InvalidArgumentException(sprintf('Could not load type "%s"', $name));
                }
            }

            $this->actionTypes[$name] = $this->resolveActionType($type);
        }

        return $this->actionTypes[$name];
    }

    /**
     * Wraps a type into a ResolvedActionTypeInterface implementation
     * and connects it with its parent type.
     *
     * @param Action\ActionTypeInterface $type The type to resolve
     *
     * @return Action\ResolvedActionTypeInterface The resolved type
     */
    private function resolveActionType(Action\ActionTypeInterface $type): Action\ResolvedActionTypeInterface
    {
        $typeExtensions = [];
        $parentType = $type->getParent();
        $class = get_class($type);

        foreach ($this->extensions as $extension) {
            $typeExtensions = array_merge(
                $typeExtensions,
                $extension->getActionTypeExtensions($class)
            );
        }

        return $this->resolvedTypeFactory->createResolvedActionType(
            $type,
            $typeExtensions,
            $parentType ? $this->getActionType($parentType) : null
        );
    }

    /**
     * @inheritDoc
     */
    public function hasActionType(string $name): bool
    {
        if (isset($this->actionTypes[$name])) {
            return true;
        }

        try {
            $this->getActionType($name);
        } catch (Exception\ExceptionInterface) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getAdapterFactory(string $name): Source\AdapterFactoryInterface
    {
        if (!isset($this->adapterFactories[$name])) {
            $adapter = null;

            foreach ($this->extensions as $extension) {
                if ($extension->hasAdapterFactory($name)) {
                    $adapter = $extension->getAdapterFactory($name);
                    break;
                }
            }

            if (!$adapter) {
                // Support fully-qualified class names
                if (class_exists($name) && is_subclass_of($name, Source\AdapterFactoryInterface::class)) {
                    $adapter = new $name();
                } else {
                    throw new Exception\InvalidArgumentException(sprintf('Could not load adapter "%s"', $name));
                }
            }

            $this->adapterFactories[$name] = $adapter;
        }

        return $this->adapterFactories[$name];
    }

    /**
     * @inheritDoc
     */
    public function hasAdapterFactory(string $name): bool
    {
        if (isset($this->adapterFactories[$name])) {
            return true;
        }

        try {
            $this->getAdapterFactory($name);
        } catch (Exception\ExceptionInterface) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}

<?php

namespace Ekyna\Component\Table;

/**
 * Class TableRegistry
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Registry implements RegistryInterface
{
    /**
     * Extensions
     * @var Extension\TableExtensionInterface[] An array of TableExtensionInterface
     */
    private $extensions = [];

    /**
     * @var array
     */
    private $tableTypes = [];

    /**
     * @var array
     */
    private $columnTypes = [];

    /**
     * @var array
     */
    private $filterTypes = [];

    /**
     * @var array
     */
    private $actionTypes = [];

    /**
     * @var ResolvedTypeFactoryInterface
     */
    private $resolvedTypeFactory;

    /**
     * @var array
     */
    private $adapterFactories;


    /**
     * Constructor
     *
     * @param Extension\TableExtensionInterface[] $extensions          An array of TableExtensionInterface
     * @param ResolvedTypeFactoryInterface        $resolvedTypeFactory The factory for resolved types
     */
    public function __construct(array $extensions, ResolvedTypeFactoryInterface $resolvedTypeFactory)
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof Extension\TableExtensionInterface) {
                throw new Exception\UnexpectedTypeException($extension, Extension\TableExtensionInterface::class);
            }
        }

        $this->extensions = $extensions;
        $this->resolvedTypeFactory = $resolvedTypeFactory;
    }

    /**
     * @inheritdoc
     */
    public function getTableType($name)
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
                if (class_exists($name) && in_array(TableTypeInterface::class, class_implements($name))) {
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
    private function resolveTableType(TableTypeInterface $type)
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
     * @inheritdoc
     */
    public function hasTableType($name)
    {
        if (isset($this->tableTypes[$name])) {
            return true;
        }

        try {
            $this->getTableType($name);
        } catch (Exception\ExceptionInterface $e) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getColumnType($name)
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
                if (class_exists($name) && in_array(Column\ColumnTypeInterface::class, class_implements($name))) {
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
    private function resolveColumnType(Column\ColumnTypeInterface $type)
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
     * @inheritdoc
     */
    public function hasColumnType($name)
    {
        if (isset($this->columnTypes[$name])) {
            return true;
        }

        try {
            $this->getColumnType($name);
        } catch (Exception\ExceptionInterface $e) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getFilterType($name)
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
                if (class_exists($name) && in_array(Filter\FilterTypeInterface::class, class_implements($name))) {
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
    private function resolveFilterType(Filter\FilterTypeInterface $type)
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
     * @inheritdoc
     */
    public function hasFilterType($name)
    {
        if (isset($this->filterTypes[$name])) {
            return true;
        }

        try {
            $this->getFilterType($name);
        } catch (Exception\ExceptionInterface $e) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getActionType($name)
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
                if (class_exists($name) && in_array(Action\ActionTypeInterface::class, class_implements($name))) {
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
    private function resolveActionType(Action\ActionTypeInterface $type)
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
     * @inheritdoc
     */
    public function hasActionType($name)
    {
        if (isset($this->actionTypes[$name])) {
            return true;
        }

        try {
            $this->getActionType($name);
        } catch (Exception\ExceptionInterface $e) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getAdapterFactory($name)
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
                if (class_exists($name) && in_array(Source\AdapterFactoryInterface::class, class_implements($name))) {
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
     * @inheritdoc
     */
    public function hasAdapterFactory($name)
    {
        if (isset($this->adapterFactories[$name])) {
            return true;
        }

        try {
            $this->getAdapterFactory($name);
        } catch (Exception\ExceptionInterface $e) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}

<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Exception\UnexpectedTypeException;
use Ekyna\Component\Table\Exception\InvalidArgumentException;

abstract class AbstractTableExtension implements TableExtensionInterface
{
    /**
     * The column types provided by this extension
     * @var TableTypeInterface[] An array of ColumnTypeInterface
     */
    private $tableTypes;

    /**
     * The column types provided by this extension
     * @var ColumnTypeInterface[] An array of ColumnTypeInterface
     */
    private $columnTypes;

    /**
     * The filter types provided by this extension
     * @var FilterTypeInterface[] An array of FilterTypeInterface
     */
    private $filterTypes;

    /**
     * {@inheritdoc}
     */
    public function getTableType($name)
    {
        if (null === $this->tableTypes) {
            $this->initTableTypes();
        }

        if (!isset($this->tableTypes[$name])) {
            throw new InvalidArgumentException(sprintf('The table type "%s" can not be loaded by this extension', $name));
        }

        return $this->tableTypes[$name];
    }

    /**
     * {@inheritdoc}
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
    public function getColumnType($name)
    {
        if (null === $this->columnTypes) {
            $this->initColumnTypes();
        }

        if (!isset($this->columnTypes[$name])) {
            throw new InvalidArgumentException(sprintf('The column type "%s" can not be loaded by this extension', $name));
        }

        return $this->columnTypes[$name];
    }

    /**
     * {@inheritdoc}
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
    public function getFilterType($name)
    {
        if (null === $this->filterTypes) {
            $this->initFilterTypes();
        }

        if (!isset($this->filterTypes[$name])) {
            throw new InvalidArgumentException(sprintf('The filter type "%s" can not be loaded by this extension', $name));
        }

        return $this->filterTypes[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasFilterType($name)
    {
        if (null === $this->filterTypes) {
            $this->initFilterTypes();
        }

        return isset($this->filterTypes[$name]);
    }

    /**
     * Registers the table types.
     *
     * @return TableTypeInterface[] An array of TableTypeInterface instances
     */
    protected function loadTableTypes()
    {
        return array();
    }

    /**
     * Registers the column types.
     *
     * @return ColumnTypeInterface[] An array of ColumnTypeInterface instances
     */
    protected function loadColumnTypes()
    {
        return array();
    }

    /**
     * Registers the filter types.
     *
     * @return FilterTypeInterface[] An array of FilterTypeInterface instances
     */
    protected function loadFilterTypes()
    {
        return array();
    }

    /**
     * Initializes the table types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of TableTypeInterface
     */
    private function initTableTypes()
    {
        $this->tableTypes = array();

        foreach ($this->loadTableTypes() as $type) {
            if (!$type instanceof TableTypeInterface) {
                throw new UnexpectedTypeException($type, 'Ekyna\Component\Table\TableTypeInterface');
            }

            $this->tableTypes[$type->getName()] = $type;
        }
    }

    /**
     * Initializes the column types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of ColumnTypeInterface
     */
    private function initColumnTypes()
    {
        $this->columnTypes = array();

        foreach ($this->loadColumnTypes() as $type) {
            if (!$type instanceof ColumnTypeInterface) {
                throw new UnexpectedTypeException($type, 'Ekyna\Component\Table\ColumnTypeInterface');
            }

            $this->columnTypes[$type->getName()] = $type;
        }
    }

    /**
     * Initializes the filter types.
     *
     * @throws UnexpectedTypeException if any registered type is not an instance of FilterTypeInterface
     */
    private function initFilterTypes()
    {
        $this->filterTypes = array();

        foreach ($this->loadFilterTypes() as $type) {
            if (!$type instanceof FilterTypeInterface) {
                throw new UnexpectedTypeException($type, 'Ekyna\Component\Table\FilterTypeInterface');
            }

            $this->filterTypes[$type->getName()] = $type;
        }
    }    
}
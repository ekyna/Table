<?php

namespace Ekyna\Component\Table;

/**
 * TableFactory
 */
class TableFactory
{
    /**
     * @var TableRegistryInterface
     */
    private $registry;

    /**
     * Initialize the TableFactory
     */
    public function __construct(TableRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Creates a column for given table
     *
     * @param \Ekyna\Component\Table\Table $table
     * @param string                        $name
     * @param string                        $type
     * @param array                         $options
     */
    public function createColumn(Table $table, $name, $type = null, array $options = array())
    {
        if(null === $type) {
            $type = 'text';
        }
        $columnType = $this->registry->getColumnType($type);
        $columnType->buildTableColumn($table, $name, $options);
    }

    /**
     * Creates a filter for given table
     *
     * @param \Ekyna\Component\Table\Table $table
     * @param string                        $name
     * @param string                        $type
     * @param array                         $options
     */
    public function createFilter(Table $table, $name, $type = null, array $options = array())
    {
        if(null === $type) {
            $type = 'text';
        }
        $filterType = $this->registry->getFilterType($type);
        $filterType->buildTableFilter($table, $name, $options);
    }

    /**
     * Returns a table builder
     *
     * @param string $entityClass
     * @param string $type
     *
     * @return \Ekyna\Component\Table\TableBuilder
     */
    public function createBuilderForEntity($entityClass, $type = 'table')
    {
        $type = $type instanceof TableTypeInterface ? $type : $this->getTableType($type);
        
        return new TableBuilder($this, $type, $entityClass);
    }

    /**
     * Returns a table builder
     *
     * @param TableTypeInterface|string $type
     *
     * @return \Ekyna\Component\Table\TableBuilder
     */
    public function createBuilder($type = 'table')
    {
        $type = $type instanceof TableTypeInterface ? $type : $this->getTableType($type);

        return new TableBuilder($this, $type);
    }

    /**
     * Returns a table type by name.
     * 
     * @param string $name The name of the type
     *
     * @return TableTypeInterface The type
     */
    public function getTableType($name)
    {
        return $this->registry->getTableType($name);
    }

    /**
     * Returns a column type by name.
     * 
     * @param string $name The name of the type
     *
     * @return ColumnTypeInterface The type
     */
    public function getColumnType($name)
    {
        return $this->registry->getColumnType($name);
    }

    /**
     * Returns a filter type by name.
     * 
     * @param string $name The name of the type
     *
     * @return FilterTypeInterface The type
     */
    public function getFilterType($name)
    {
        return $this->registry->getFilterType($name);
    }
}
<?php

namespace Ekyna\Component\Table;

/**
 * TableBuilderInterface
 */
interface TableBuilderInterface
{
    /**
     * Sets default sorting
     * 
     * @param string $property
     * @param string $dir
     * 
     * @return TableBuilder
     */
    public function setDefaultSort($property, $dir = 'ASC');
    
    /**
     * Sets max results per page
     * 
     * @param integer $max
     * 
     * @return TableBuilder
     */
    public function setMaxPerPage($max);

    /**
     * Sets the table name
     * 
     * @param string $name
     * 
     * @return TableBuilder
     */
    public function setTableName($name);

    /**
     * Sets the entity class
     * 
     * @param string $class
     * 
     * @return TableBuilder
     */
    public function setEntityClass($class);

    /**
     * Adds a column definition
     *
     * @param string $name
     * @param string $type
     * @param array $options
     * 
     * @throws Exception\InvalidArgumentException
     * 
     * @return TableBuilder
     */
    public function addColumn($name, $type = null, array $options = array());

    /**
     * Adds a filter definition
     *
     * @param string $name
     * @param string $type
     * @param array $options
     * 
     * @throws Exception\InvalidArgumentException
     * 
     * @return TableBuilder
     */
    public function addFilter($name, $type = null, array $options = array());

    /**
     * Returns the Table
     * 
     * @throws Exception\RuntimeException
     *
     * @return \Ekyna\Component\Table\Table
     */
    public function getTable($name = null);
}
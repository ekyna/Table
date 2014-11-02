<?php

namespace Ekyna\Component\Table;

/**
 * Interface TableBuilderInterface
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface TableBuilderInterface
{
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
    public function getTable();
}
<?php

namespace Ekyna\Component\Table;

interface TableTypeInterface
{
    /**
     * Builds the table
     * 
     * @param TableBuilderInterface $builder
     */
    public function buildTable(TableBuilderInterface $builder);

    /**
     * Create a table view
     * 
     * @return TableView
     */
    public function createView();

    /**
     * Returns the entity class
     */
    public function getEntityClass();

    /**
     * Returns the type name
     */
    public function getName();
}
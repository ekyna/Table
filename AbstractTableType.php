<?php

namespace Ekyna\Component\Table;

abstract class AbstractTableType implements TableTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder)
    {}

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        // TODO: code from TableGenerator ...
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return null;
    }
}
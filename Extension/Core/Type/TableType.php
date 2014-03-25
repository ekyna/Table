<?php

namespace Ekyna\Component\Table\Extension\Core\Type;

use Ekyna\Component\Table\AbstractTableType;

class TableType extends AbstractTableType
{
    /**
     * Returns the type name
     */
    public function getName()
    {
        return 'table';
    }
}
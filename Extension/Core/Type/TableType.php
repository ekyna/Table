<?php

namespace Ekyna\Component\Table\Extension\Core\Type;

use Ekyna\Component\Table\AbstractTableType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class TableType
 * @package Ekyna\Component\Table\Extension\Core\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TableType extends AbstractTableType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {

    }

    /**
     * Returns the type name
     */
    public function getName()
    {
        return 'table';
    }
}

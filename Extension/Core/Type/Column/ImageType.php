<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Column;

/**
 * Class ImageType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImageType extends PropertyType
{
    /**
     * {@inheritDoc}
     */
    public function buildViewColumn(Column $column, Table $table, array $options)
    {
        parent::buildViewColumn($column, $table, $options);

        $column->setVar('sortable', false);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'image';
    }
}

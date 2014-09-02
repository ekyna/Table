<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class DatetimeType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class DatetimeType extends PropertyType
{
    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);
        $cell->setVars(array(
            'value'  => $table->getCurrentRowData($options['property_path'])->format('d/m/Y H:i'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'datetime';
    }
}

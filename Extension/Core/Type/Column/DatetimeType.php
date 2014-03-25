<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\View\Cell;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * DatetimeType
 */
class DatetimeType extends PropertyType
{
    public function buildViewCell(Cell $cell, PropertyAccessor $propertyAccessor, $entity, array $options)
    {
        parent::buildViewCell($cell, $propertyAccessor, $entity, $options);
        $cell->setVars(array(
            'value'  => $propertyAccessor->getValue($entity, $options['property_path'])->format('d/m/Y H:i'),
        ));
    }

    public function getName()
    {
    	return 'datetime';
    }
}
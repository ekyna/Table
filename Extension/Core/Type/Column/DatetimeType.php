<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $formats = ['none', 'short', 'medium', 'long', 'full'];

        $resolver
            ->setDefaults(array(
                'date_format' => 'short',
                'time_format' => 'short',
            ))
            ->setAllowedValues(array(
                'date_format' => $formats,
                'time_format' => $formats,
            ))
            ->setAllowedTypes(array(
                'date_format' => 'string',
                'time_format' => 'string',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);
        $cell->setVars(array(
            //'value'  => $table->getCurrentRowData($options['property_path'])->format('d/m/Y H:i'),
            'date_format' => $options['date_format'],
            'time_format' => $options['time_format'],
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

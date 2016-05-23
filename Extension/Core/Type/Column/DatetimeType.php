<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class DatetimeType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DatetimeType extends PropertyType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $formats = ['none', 'short', 'medium', 'long', 'full'];

        $resolver->setDefaults([
            'date_format' => 'short',
            'time_format' => 'short',
        ]);

        $resolver->setAllowedValues('date_format', array_values($formats));
        $resolver->setAllowedValues('time_format', array_values($formats));


        $resolver->setAllowedTypes('date_format', 'string');
        $resolver->setAllowedTypes('time_format', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);
        $cell->setVars([
            //'value'  => $table->getCurrentRowData($options['property_path'])->format('d/m/Y H:i'),
            'date_format' => $options['date_format'],
            'time_format' => $options['time_format'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'datetime';
    }
}

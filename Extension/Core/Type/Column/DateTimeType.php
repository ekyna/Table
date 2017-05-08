<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Column\AbstractColumnType;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateTimeType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DateTimeType extends AbstractColumnType
{
    /**
     * @inheritDoc
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options)
    {
        $view->vars['date_format'] = $options['date_format'];
        $view->vars['time_format'] = $options['time_format'];
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $formats = ['none', 'short', 'medium', 'long', 'full'];

        $resolver
            ->setDefaults([
                'date_format' => 'short',
                'time_format' => 'short',
                'alignment'   => 'right',
            ])
            ->setAllowedTypes('date_format', 'string')
            ->setAllowedTypes('time_format', 'string')
            ->setAllowedValues('date_format', array_values($formats))
            ->setAllowedValues('time_format', array_values($formats));
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return PropertyType::class;
    }
}

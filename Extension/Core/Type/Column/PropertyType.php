<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\AbstractColumnType;
use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Column;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PropertyType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class PropertyType extends AbstractColumnType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'sortable'      => false,
            'filterable'    => false,
            'label'         => function (Options $options) {
                return ucfirst($options['name']);
            },
            'property_path' => function (Options $options) {
                return $options['name'];
            },
        ]);

        $resolver->setRequired(['sortable', 'label', 'property_path']);

        $resolver->setAllowedTypes('sortable', 'bool');
        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedTypes('property_path', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewColumn(Column $column, Table $table, array $options)
    {
        parent::buildViewColumn($column, $table, $options);

        $column->setVars([
            'label'    => $options['label'],
            'sortable' => $options['sortable'],
            'sort_by'  => $options['property_path'],
            'sort_dir' => $options['sort_dir'],
            'sorted'   => $options['sorted'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $cell->setVars([
            'value'  => $table->getCurrentRowData($options['property_path']),
            'sorted' => $options['sorted'],
        ]);
    }
}

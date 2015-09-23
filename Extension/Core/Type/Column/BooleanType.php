<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class BooleanType extends PropertyType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'true_label'  => 'ekyna_core.value.yes',
            'false_label' => 'ekyna_core.value.no',
            'true_class'  => 'label-success',
            'false_class' => 'label-danger',
            'route_name'  => null,
            'route_parameters' => [],
            'route_parameters_map' => [],
        ]);
        $resolver->setRequired(['route_name']);
        $resolver->setAllowedTypes([
            'true_label'  => 'string',
            'false_label' => 'string',
            'true_class'  => 'string',
            'false_class' => 'string',
            'route_name'  => ['null', 'string'],
            'route_parameters' => 'array',
            'route_parameters_map' => 'array',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $parameters = $options['route_parameters'];
        foreach ($options['route_parameters_map'] as $key => $propertyPath) {
            $parameters[$key] = $table->getCurrentRowData($propertyPath);
        }

        $cell->setVars([
            'label'      => $cell->vars['value'] ? $options['true_label'] : $options['false_label'],
            'class'      => $cell->vars['value'] ? $options['true_class'] : $options['false_class'],
            'route'      => $options['route_name'],
            'parameters' => $parameters,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'boolean';
    }
} 
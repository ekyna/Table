<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class BooleanType extends PropertyType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'true_label'            => 'ekyna_core.value.yes',
            'false_label'           => 'ekyna_core.value.no',
            'true_class'            => 'label-success',
            'false_class'           => 'label-danger',
            'route_name'            => null,
            'route_parameters'      => [],
            'route_parameters_map'  => [],
            'disable_property_path' => null,
        ]);
        $resolver->setRequired(['route_name']);

        $resolver->setAllowedTypes('true_label', 'string');
        $resolver->setAllowedTypes('false_label', 'string');
        $resolver->setAllowedTypes('true_class', 'string');
        $resolver->setAllowedTypes('false_class', 'string');
        $resolver->setAllowedTypes('route_name', ['null', 'string']);
        $resolver->setAllowedTypes('route_parameters', 'array');
        $resolver->setAllowedTypes('route_parameters_map', 'array');
        $resolver->setAllowedTypes('disable_property_path', ['null', 'string']);
    }

    /**
     * @inheritdoc
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $route = $options['route_name'];
        if (null !== $disablePath = $options['disable_property_path']) {
            if ($table->getCurrentRowData($disablePath)) {
                $route = null;
            }
        }

        if (null !== $route) {
            $parameters = $options['route_parameters'];
            foreach ($options['route_parameters_map'] as $key => $propertyPath) {
                $parameters[$key] = $table->getCurrentRowData($propertyPath);
            }
        } else {
            $parameters = [];
        }

        $cell->setVars([
            'label'      => $cell->vars['value'] ? $options['true_label'] : $options['false_label'],
            'class'      => $cell->vars['value'] ? $options['true_class'] : $options['false_class'],
            'route'      => $route,
            'parameters' => $parameters,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'boolean';
    }
}

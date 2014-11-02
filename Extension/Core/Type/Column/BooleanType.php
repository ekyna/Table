<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;
use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'true_label' => 'ekyna_core.value.yes',
            'false_label' => 'ekyna_core.value.no',
            'route_name' => null,
            'route_parameters_map' => null,
        ));
        $resolver->setRequired(array('route_name'));
        $resolver->setAllowedTypes(array(
            'route_name'           => array('null', 'string'),
            'route_parameters_map' => array('null', 'array'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);
        $parameters = array();
        if (is_array($options['route_parameters_map'])) {
            foreach ($options['route_parameters_map'] as $parameter => $propertyPath) {
                $parameters[$parameter] = $table->getCurrentRowData($propertyPath);
            }
        }
        $cell->setVars(array(
            'label'      => $cell->vars['value'] ? $options['true_label'] : $options['false_label'],
            'route'      => $options['route_name'],
            'parameters' => $parameters,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'boolean';
    }
} 
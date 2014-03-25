<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\AbstractColumnType;
use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\TableGenerator;
use Ekyna\Component\Table\View\Column;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ActionsType
 */
class ActionsType extends AbstractColumnType
{
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'label'   => '',
            'buttons' => array(),
        ));
        $resolver->setRequired(array('buttons'));
        $resolver->setAllowedTypes(array(
            'label'   => 'string',
            'buttons' => 'array',
        ));
    }

    private function configureButtonOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label'                 => '',
            'icon'                  => '',
            'class'                 => 'default',
            'route_name'            => null,
            'route_parameters_map'  => array(),
            'disabled'              => false,
            'disable_property_path' => '',
        ));
        $resolver->setRequired(array('label', 'route_name', 'route_parameters_map'));
        $resolver->setAllowedTypes(array(
            'label'                 => 'string',
            'route_name'            => 'string',
            'route_parameters_map'  => 'array',
            'disabled'              => 'bool',
            'disable_property_path' => 'string',
        ));
    }

    public function buildTableColumn(Table $table, $name, array $options = array())
    {
        if(!isset($options['buttons']) || 0 === count($options['buttons']) || (bool) count(array_filter(array_keys($options['buttons']), 'is_string'))) {
            throw new InvalidArgumentException('The "buttons" options should be defined as an array of button options.');
        }

        $buttonResolver = new OptionsResolver();
        $this->configureButtonOptions($buttonResolver);

        $tmp = array();
        foreach($options['buttons'] as $buttonOptions) {
            $tmp[] = $buttonResolver->resolve($buttonOptions);
        }
        $options['buttons'] = $tmp;

        parent::buildTableColumn($table, $name, $options);
    }

    public function buildViewColumn(Column $column, TableGenerator $generator, array $options)
    {
        parent::buildViewColumn($column, $generator, $options);
    	$column->setVars(array(
        	'label' => $options['label'],
    	));
    }

    public function buildViewCell(Cell $cell, PropertyAccessor $propertyAccessor, $entity, array $options)
    {
        parent::buildViewCell($cell, $propertyAccessor, $entity, $options);

        $buttons = array();
        foreach($options['buttons'] as $buttonOptions) {
            $parameters = array();
            foreach($buttonOptions['route_parameters_map'] as $parameter => $propertyPath) {
                $parameters[$parameter] = $propertyAccessor->getValue($entity, $propertyPath);
            }
            $disabled = $buttonOptions['disabled'];
            if(0 < strlen($buttonOptions['disable_property_path'])) {
                $disabled = $propertyAccessor->getValue($entity, $buttonOptions['disable_property_path']);
            }
            $buttons[] = array(
                'disabled'   => $disabled,
                'label'      => $buttonOptions['label'],
                'icon'       => $buttonOptions['icon'],
            	'class'      => $buttonOptions['class'],
            	'route'      => $buttonOptions['route_name'],
                'parameters' => $parameters,
            );
        }
        $cell->setVars(array(
            'buttons' => $buttons,
        ));
    }

    public function getName()
    {
    	return 'actions';
    }
}
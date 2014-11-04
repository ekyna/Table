<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\AbstractColumnType;
use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\TableConfig;
use Ekyna\Component\Table\View\Column;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ActionsType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ActionsType extends AbstractColumnType
{
    /**
     * {@inheritDoc}
     */
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

    /**
     * Configures the button options resolver.
     * 
     * @param OptionsResolverInterface $resolver
     */
    protected function configureButtonOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label'                 => '',
            'icon'                  => '',
            'class'                 => 'default',
            'route_name'            => null,
            'route_parameters'      => array(),
            'route_parameters_map'  => array(),
            'disabled'              => false,
            'disable_property_path' => '',
        ));
        $resolver->setRequired(array('label', 'route_name', 'route_parameters_map'));
        $resolver->setAllowedTypes(array(
            'label'                 => 'string',
            'route_name'            => 'string',
            'route_parameters'      => 'array',
            'route_parameters_map'  => 'array',
            'disabled'              => 'bool',
            'disable_property_path' => 'string',
        ));
    }

    /**
     * Prepares the buttons.
     *
     * @param TableConfig $config
     * @param array $buttonsOptions
     * 
     * @return array
     */
    protected function prepareButtons(TableConfig $config, array $buttonsOptions)
    {
        $buttonResolver = new OptionsResolver();
        $this->configureButtonOptions($buttonResolver);

        $tmp = array();
        foreach($buttonsOptions as $buttonOptions) {
            $tmp[] = $buttonResolver->resolve($buttonOptions);
        }
        return $tmp;
    }

    /**
     * {@inheritDoc}
     */
    public function buildTableColumn(TableConfig $config, $name, array $options = array())
    {
        $options['buttons'] = $this->prepareButtons($config, $options['buttons']);

        if (0 < count($options['buttons'])) {
            parent::buildTableColumn($config, $name, $options);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function buildViewColumn(Column $column, Table $table, array $options)
    {
        parent::buildViewColumn($column, $table, $options);

    	$column->setVars(array(
        	'label' => $options['label'],
    	));
    }

    /**
     * {@inheritDoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $buttons = array();
        foreach($options['buttons'] as $buttonOptions) {
            $parameters = $buttonOptions['route_parameters'];
            foreach($buttonOptions['route_parameters_map'] as $parameter => $propertyPath) {
                $parameters[$parameter] = $table->getCurrentRowData($propertyPath);
            }
            $disabled = $buttonOptions['disabled'];
            if(0 < strlen($buttonOptions['disable_property_path'])) {
                $disabled = $table->getCurrentRowData($buttonOptions['disable_property_path']);
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

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
    	return 'actions';
    }
}

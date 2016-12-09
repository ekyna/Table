<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\AbstractColumnType;
use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\TableConfig;
use Ekyna\Component\Table\View\Column;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ActionsType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ActionsType extends AbstractColumnType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label'    => '',
            'buttons'  => [],
            'position' => 999,
        ]);

        $resolver->setRequired(['buttons']);

        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedTypes('buttons', 'array');
    }

    /**
     * Configures the button options resolver.
     *
     * @param OptionsResolver $resolver
     */
    protected function configureButtonOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label'                 => '',
            'icon'                  => '',
            'class'                 => 'default',
            'route_name'            => null,
            'route_parameters'      => [],
            'route_parameters_map'  => [],
            'disabled'              => false,
            'disable_property_path' => '',
        ]);

        $resolver->setRequired(['label', 'route_name', 'route_parameters_map']);

        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedTypes('route_name', 'string');
        $resolver->setAllowedTypes('route_parameters', 'array');
        $resolver->setAllowedTypes('route_parameters_map', 'array');
        $resolver->setAllowedTypes('disabled', 'bool');
        $resolver->setAllowedTypes('disable_property_path', 'string');
    }

    /**
     * Prepares the buttons.
     *
     * @param TableConfig $config
     * @param array       $buttonsOptions
     *
     * @return array
     */
    protected function prepareButtons(TableConfig $config, array $buttonsOptions)
    {
        $buttonResolver = new OptionsResolver();
        $this->configureButtonOptions($buttonResolver);

        $tmp = [];
        foreach ($buttonsOptions as $buttonOptions) {
            $tmp[] = $buttonResolver->resolve($buttonOptions);
        }

        return $tmp;
    }

    /**
     * @inheritdoc
     */
    public function buildTableColumn(TableConfig $config, $name, array $options = [])
    {
        $options['buttons'] = $this->prepareButtons($config, $options['buttons']);

        if (0 < count($options['buttons'])) {
            parent::buildTableColumn($config, $name, $options);
        }
    }

    /**
     * @inheritdoc
     */
    public function buildViewColumn(Column $column, Table $table, array $options)
    {
        parent::buildViewColumn($column, $table, $options);

        $column->setVars([
            'label' => $options['label'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $buttons = [];
        foreach ($options['buttons'] as $buttonOptions) {
            $parameters = $buttonOptions['route_parameters'];
            foreach ($buttonOptions['route_parameters_map'] as $parameter => $propertyPath) {
                $parameters[$parameter] = $table->getCurrentRowData($propertyPath);
            }
            $disabled = $buttonOptions['disabled'];
            if (0 < strlen($buttonOptions['disable_property_path'])) {
                $disabled = $table->getCurrentRowData($buttonOptions['disable_property_path']);
            }
            $buttons[] = [
                'disabled'   => $disabled,
                'label'      => $buttonOptions['label'],
                'icon'       => $buttonOptions['icon'],
                'class'      => $buttonOptions['class'],
                'route'      => $buttonOptions['route_name'],
                'parameters' => $parameters,
            ];
        }
        $cell->setVars([
            'buttons' => $buttons,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'actions';
    }
}

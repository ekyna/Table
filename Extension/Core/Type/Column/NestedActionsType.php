<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class NestedActionsType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class NestedActionsType extends ActionsType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'disable_property_path' => '',
            'left_property_path'    => 'left',
            'right_property_path'   => 'right',
            'parent_property_path'  => 'parent',
            'new_child_route'       => null,
            'move_up_route'         => null,
            'move_down_route'       => null,
            'routes_parameters'     => array(),
            'routes_parameters_map' => array(),
        ));
        $resolver->setRequired(array(
            'move_up_route',
            'move_down_route',
            'new_child_route',
            'routes_parameters_map',
        ));
        $resolver->setAllowedTypes(array(
            'disable_property_path' => 'string',
            'left_property_path'    => 'string',
            'right_property_path'   => 'string',
            'parent_property_path'  => 'string',
            'new_child_route'       => 'string',
            'move_up_route'         => 'string',
            'move_down_route'       => 'string',
            'routes_parameters'     => 'array',
            'routes_parameters_map' => 'array',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $disabled = false;
        if (0 < strlen($options['disable_property_path'])) {
            $disabled = $table->getCurrentRowData($options['disable_property_path']);
        }

        $newChildButton = $moveUpButton = $moveDownButton = array(
            'disabled'   => $disabled,
            'label'      => 'Ajouter',
            'class'      => 'primary',
        );
        $newChildButton['icon']  = 'plus';
        $newChildButton['class'] = 'success';
        $moveUpButton['icon']    = 'arrow-up';
        $moveUpButton['label']   = 'Déplacer vers le haut';
        $moveDownButton['icon']  = 'arrow-down';
        $moveDownButton['label'] = 'Déplacer vers le bas';

        if (!$disabled) {
            $parameters = $options['routes_parameters'];
            foreach($options['routes_parameters_map'] as $parameter => $propertyPath) {
                $parameters[$parameter] = $table->getCurrentRowData($propertyPath);
            }
            $newChildButton['route'] = $options['new_child_route'];
            $newChildButton['parameters'] = $parameters;

            $left = $table->getCurrentRowData($options['left_property_path']);
            $right = $table->getCurrentRowData($options['right_property_path']);

            if (null !== $parent = $table->getCurrentRowData($options['parent_property_path'])) {
                $accessor = $table->getPropertyAccessor();
                $parentLeft = $accessor->getValue($parent, $options['left_property_path']);
                $parentRight = $accessor->getValue($parent, $options['right_property_path']);

                if ($left === $parentLeft + 1) {
                    $moveUpButton['disabled'] = true;
                } else {
                    $moveUpButton['route'] = $options['move_up_route'];
                    $moveUpButton['parameters'] = $parameters;
                }

                if ($right === $parentRight - 1) {
                    $moveDownButton['disabled'] = true;
                } else {
                    $moveDownButton['route'] = $options['move_down_route'];
                    $moveDownButton['parameters'] = $parameters;
                }
            } else { // Roots can't be moved
                $moveUpButton['disabled'] = true;
                $moveDownButton['disabled'] = true;
            }
        }
        array_unshift($cell->vars['buttons'], $moveDownButton);
        array_unshift($cell->vars['buttons'], $moveUpButton);
        array_unshift($cell->vars['buttons'], $newChildButton);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'nested_actions';
    }
}

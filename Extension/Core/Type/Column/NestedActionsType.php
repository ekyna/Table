<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\View\Cell;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * NestedActionsType
 */
class NestedActionsType extends ActionsType
{
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
            'routes_parameters_map' => null,
        ));
        $resolver->setRequired(array('move_up_route', 'move_down_route', 'new_child_route', 'routes_parameters_map'));
        $resolver->setAllowedTypes(array(
            'disable_property_path' => 'string',
            'new_child_route'       => 'string',
            'move_up_route'         => 'string',
            'move_down_route'       => 'string',
            'routes_parameters_map' => 'array',
        ));
    }

    public function buildViewCell(Cell $cell, PropertyAccessor $propertyAccessor, $entity, array $options)
    {
        parent::buildViewCell($cell, $propertyAccessor, $entity, $options);
        $disabled = false;
        if(0 < strlen($options['disable_property_path'])) {
            $disabled = $propertyAccessor->getValue($entity, $options['disable_property_path']);
        }

        $newChildButton = $moveUpButton = $moveDownButton = array(
            'disabled'   => $disabled,
            'label'      => 'Ajouter',
            'class'      => 'primary',
        );
        $newChildButton['icon'] = 'plus';
        $newChildButton['class'] = 'success';
        $moveUpButton['icon'] = 'arrow-up';
        $moveUpButton['label'] = 'Déplacer vers le haut';
        $moveDownButton['icon'] = 'arrow-down';
        $moveDownButton['label'] = 'Déplacer vers le bas';

        if(!$disabled) {
            $parameters = array();
            foreach($options['routes_parameters_map'] as $parameter => $propertyPath) {
                $parameters[$parameter] = $propertyAccessor->getValue($entity, $propertyPath);
            }
            $newChildButton['route'] = $options['new_child_route'];
            $newChildButton['parameters'] = $parameters;

            if(null !== $parent = $propertyAccessor->getValue($entity, $options['parent_property_path'])) {

                $left = $propertyAccessor->getValue($entity, $options['left_property_path']);
                $right = $propertyAccessor->getValue($entity, $options['right_property_path']);
                $parentLeft = $propertyAccessor->getValue($parent, $options['left_property_path']);
                $parentRight = $propertyAccessor->getValue($parent, $options['right_property_path']);

                if($entity->getLeft() === $parent->getLeft() + 1) {
                    $moveUpButton['disabled'] = true;
                }else{
                    $moveUpButton['route'] = $options['move_up_route'];
                    $moveUpButton['parameters'] = $parameters;
                }

                if($entity->getRight() === $parent->getRight() - 1) {
                    $moveDownButton['disabled'] = true;
                }else{
                    $moveDownButton['route'] = $options['move_down_route'];
                    $moveDownButton['parameters'] = $parameters;
                }
            }
        }
        array_unshift($cell->vars['buttons'], $moveDownButton);
        array_unshift($cell->vars['buttons'], $moveUpButton);
        array_unshift($cell->vars['buttons'], $newChildButton);
    }
    
    public function getName()
    {
        return 'nested_actions';
    }
}
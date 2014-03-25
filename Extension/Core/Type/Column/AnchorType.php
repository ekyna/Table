<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * AnchorType
 */
class AnchorType extends PropertyType
{
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'route_name' => null,
            'route_parameters_map' => array(),
        ));
        $resolver->setRequired(array('route_name'));
        $resolver->setAllowedTypes(array(
            'route_name'           => 'string',
            'route_parameters_map' => 'array',
        ));
    }

    public function buildViewCell(Cell $cell, PropertyAccessor $propertyAccessor, $entity, array $options)
    {
        parent::buildViewCell($cell, $propertyAccessor, $entity, $options);
        $parameters = array();
        foreach($options['route_parameters_map'] as $parameter => $propertyPath) {
            $parameters[$parameter] = $propertyAccessor->getValue($entity, $propertyPath);
        }
        $cell->setVars(array(
            'route'      => $options['route_name'],
            'parameters' => $parameters,
        ));
    }

    public function getName()
    {
        return 'anchor';
    }
}
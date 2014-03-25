<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class NestedAnchorType extends AnchorType
{
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'right_property_path'  => 'right',
            'parent_property_path' => 'parent',
        ));
        $resolver->setAllowedTypes(array(
            'right_property_path'  => 'string',
            'parent_property_path' => 'string',
        ));
    }

    public function buildViewCell(Cell $cell, PropertyAccessor $propertyAccessor, $entity, array $options)
    {
        parent::buildViewCell($cell, $propertyAccessor, $entity, $options);
        $cell->setVars(array(
        	'tree' => $this->getParentTreeClasses($entity, $propertyAccessor, $options)
        ));
    }

    private function getParentTreeClasses($entity, PropertyAccessor $propertyAccessor, array $options, $level = 0)
    {
        if(null !== $parent = $propertyAccessor->getValue($entity, $options['parent_property_path'])) {
            $classes = $this->getParentTreeClasses($parent, $propertyAccessor, $options, $level+1);

            $right = $propertyAccessor->getValue($entity, $options['right_property_path']);
            $parentRight = $propertyAccessor->getValue($parent, $options['right_property_path']);

            if($right === $parentRight - 1) {
                $classes[] = 0 === $level ? 'last-child' : '';
            }else{
                $classes[] = 0 === $level ? 'child' : 'continue';
            }
            return $classes;
        }
        return array();
    }

    public function getName()
    {
        return 'nested_anchor';
    }
}
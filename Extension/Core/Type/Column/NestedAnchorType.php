<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class NestedAnchorType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class NestedAnchorType extends AnchorType
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $cell->setVars(array(
        	'tree' => $this->getParentTreeClasses($table->getCurrentRowData(), $table->getPropertyAccessor(), $options)
        ));
    }

    /**
     * {@inheritdoc}
     */
    private function getParentTreeClasses($data, PropertyAccessor $propertyAccessor, array $options, $level = 0)
    {
        if(null !== $parent = $propertyAccessor->getValue($data, $options['parent_property_path'])) {
            $classes = $this->getParentTreeClasses($parent, $propertyAccessor, $options, $level+1);

            $right = $propertyAccessor->getValue($data, $options['right_property_path']);
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nested_anchor';
    }
}

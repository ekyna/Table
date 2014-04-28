<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\AbstractColumnType;
use Ekyna\Component\Table\TableGenerator;
use Ekyna\Component\Table\Util\ColumnSort;
use Ekyna\Component\Table\View\Column;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
// use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;

/**
 * PropertyType
 */
abstract class PropertyType extends AbstractColumnType
{
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'sortable'      => false,
            'filterable'    => false,
            'label'         => function (Options $options) {
                return ucfirst($options['name']);
            },
            'property_path' => function (Options $options) {
                return $options['name'];
            },
        ));
        $resolver->setRequired(array('sortable', 'label', 'property_path'));
        $resolver->setAllowedTypes(array(
            'sortable'      => 'bool',
            'label'         => 'string',
            'property_path' => 'string',
        ));
    }

    public function buildViewColumn(Column $column, TableGenerator $generator, array $options)
    {
        parent::buildViewColumn($column, $generator, $options);
        $sort = $options['sortable'] ? $generator->getUserVar($options['full_name'].'_sort', ColumnSort::NONE) : ColumnSort::NONE;
    	$column->setVars(array(
        	'label'    => $options['label'],
        	'sortable' => $options['sortable'],
        	'sort_by'  => $options['property_path'],
        	'sort_dir' => $generator->getUserVar($options['full_name'].'_sort', ColumnSort::NONE),
    	    'sorted'   => $sort !== ColumnSort::NONE,
    	));
    }

    public function buildViewCell(Cell $cell, PropertyAccessor $propertyAccessor, $entity, array $options)
    {
        parent::buildViewCell($cell, $propertyAccessor, $entity, $options);
        /*try {
            $value = $propertyAccessor->getValue($entity, $options['property_path']);
        }catch(UnexpectedTypeException $e) {
            $value = '&nbsp;-&nbsp;';
        }*/
        $cell->setVars(array(
            'value'  => $propertyAccessor->getValue($entity, $options['property_path']),
        ));
    }
}
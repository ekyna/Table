<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Doctrine\Common\Collections\Collection;
use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

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
            'left_property_path'  => 'left',
            'right_property_path'  => 'right',
            'parent_property_path' => 'parent',
            'children_property_path' => 'children',
        ));
        $resolver->setAllowedTypes(array(
            'left_property_path'  => 'string',
            'right_property_path'  => 'string',
            'parent_property_path' => 'string',
            'children_property_path' => 'string',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $data = $table->getCurrentRowData();

        $cell->setVars(array(
        	'nodes' => $this->getTreeNodes($data, $table->getPropertyAccessor(), $options),
        ));
    }

    /**
     * {@inheritdoc}
     */
    private function getTreeNodes($data, PropertyAccessorInterface $propertyAccessor, array $options, $level = 0)
    {
        $nodes = [];

        if (null !== $parent = $propertyAccessor->getValue($data, $options['parent_property_path'])) {
            $nodes = $this->getTreeNodes($parent, $propertyAccessor, $options, $level+1);

            $left = $propertyAccessor->getValue($data, $options['left_property_path']);
            $right = $propertyAccessor->getValue($data, $options['right_property_path']);
            $parentRight = $propertyAccessor->getValue($parent, $options['right_property_path']);

            $type = 'node';
            $classes = $childrenIds = [];

            $isLast = $right === $parentRight - 1;

            if ($level === 0) {
                if ($right - $left > 1) {
                    $type = 'button';
                    $classes[] = 'toggle';
                    $classes[] = 'toggle-close';
                    $childrenIds = $this->getChildrenIds($data, $propertyAccessor, $options);
                } else {
                    $classes[] = 'child';
                }
                if ($isLast) {
                    $classes[] = 'last';
                }
            } elseif (!$isLast) {
                $classes[] = 'continue';
            }

            $nodes[] = array(
                'type'  => $type,
                'class' => implode(' ', $classes),
                'children' => json_encode($childrenIds),
            );
        }

        return $nodes;
    }

    /**
     * Returns the children ids.
     *
     * @param mixed $data
     * @param PropertyAccessorInterface $propertyAccessor
     * @param array $options
     * @return array
     */
    private function getChildrenIds($data, PropertyAccessorInterface $propertyAccessor, array $options)
    {
        $children = $propertyAccessor->getValue($data, $options['children_property_path']);
        if ($children instanceof Collection) {
            $children = $children->toArray();
            if (!empty($children)) {
                return array_map(function ($child) use ($propertyAccessor) {
                    return $propertyAccessor->getValue($child, 'id');
                }, $children);
            }
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

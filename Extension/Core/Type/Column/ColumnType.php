<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Column;

use Ekyna\Component\Table\Column\AbstractColumnType;
use Ekyna\Component\Table\Column\ColumnBuilderInterface;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Ekyna\Component\Table\View\HeadView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ColumnType
 * @package Ekyna\Component\Table\Extension\Core\Type\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ColumnType extends AbstractColumnType
{
    /**
     * @inheritDoc
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $options)
    {
        if (null === $propertyPath = $options['property_path']) {
            $propertyPath = $builder->getName();
        }

        $builder
            ->setLabel($options['label'] ?: ucfirst($builder->getName()))
            ->setPosition($options['position'])
            ->setPropertyPath($propertyPath)
            ->setSortable($options['sortable'] && !empty($propertyPath));
    }

    /**
     * @inheritDoc
     */
    public function buildHeadView(HeadView $view, ColumnInterface $column, array $options)
    {
        $name = $column->getName();
        $tableName = $column->getTable()->getName();

        $id = sprintf('%s_%s', $tableName, $name);
        $fullName = sprintf('%s[%s]', $tableName, $name);

        /*$blockName = $options['block_name'] ?: $name;
        $uniqueBlockPrefix = sprintf('%s_%s_head', $view->table->vars['unique_block_prefix'], $blockName);

        $blockPrefixes = [];
        for ($type = $column->getConfig()->getType(); null !== $type; $type = $type->getParent()) {
            array_unshift($blockPrefixes, $type->getBlockPrefix());
        }
        $blockPrefixes[] = $uniqueBlockPrefix;

        $cacheKey = $uniqueBlockPrefix . '_' . $column->getConfig()->getType()->getBlockPrefix() . '_head';*/

        $sortable = $column->getTable()->getConfig()->isSortable() && $column->getConfig()->isSortable();

        // Attributes
        $attr = $options['head_attr'];
        $sortLink = null;

        // Classes
        $classes = isset($attr['class']) ? explode(' ', $attr['class']) : [];
        if ($sortable) {
            $classes[] = 'sort';
            if ($column->isSorted()) {
                $classes[] = 'sorted';
            }
            $sortLink = [
                'dir'  => $column->getSortDirection(),
                'href' => $column->getTable()->getParametersHelper()->getSortHref($column),
            ];
        }
        if (!empty($classes)) {
            $attr['class'] = implode(' ', $classes);
        }

        $view->vars = array_replace($view->vars, [
            'id'                 => $id,
            'name'               => $name,
            'full_name'          => $fullName,
            'label'              => $column->getLabel(),
            'translation_domain' => $options['translation_domain'],
            'attr'               => $attr,
            'sort_link'          => $sortLink,
            //'block_prefixes'      => $blockPrefixes,
            //'unique_block_prefix' => $uniqueBlockPrefix,
            //'cache_key'           => $cacheKey,
            'block_prefix'       => $column->getConfig()->getType()->getBlockPrefix(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options)
    {
        $name = $column->getName();

        /*$blockName = $options['block_name'] ?: $name;
        $uniqueBlockPrefix = sprintf('%s_%s_cell', $view->row->table->vars['unique_block_prefix'], $blockName);

        $blockPrefixes = [];
        for ($type = $column->getConfig()->getType(); null !== $type; $type = $type->getParent()) {
            array_unshift($blockPrefixes, $type->getBlockPrefix());
        }
        $blockPrefixes[] = $uniqueBlockPrefix;

        $cacheKey = $uniqueBlockPrefix . '_' . $column->getConfig()->getType()->getBlockPrefix() . '_cell';*/

        // Attributes
        $attr = $options['cell_attr'];
        $sortLink = null;

        // Classes
        $classes = isset($attr['class']) ? explode(' ', $attr['class']) : [];
        if ($column->isSorted()) {
            $classes[] = 'sorted';
        }
        if (null !== $align = $options['alignment']) {
            $classes[] = 'text-' . $align;
        }
        if (!empty($classes)) {
            $attr['class'] = implode(' ', $classes);
        }

        $view->vars = array_replace($view->vars, [
            'name'         => $name,
            'attr'         => $attr,
            //'block_prefixes'      => $blockPrefixes,
            //'unique_block_prefix' => $uniqueBlockPrefix,
            //'cache_key'           => $cacheKey,
            'block_prefix' => $options['block_prefix'] ?: $column->getConfig()->getType()->getBlockPrefix(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'block_prefix'       => null,
                'position'           => 0,
                'property_path'      => null,
                'sortable'           => true,
                'label'              => null,
                'translation_domain' => null,
                'alignment'          => null,
                'head_attr'          => [],
                'cell_attr'          => [],
            ])
            ->setAllowedTypes('block_prefix', ['null', 'string'])
            ->setAllowedTypes('position', 'int')
            ->setAllowedTypes('property_path', ['null', 'bool', 'string'])
            ->setAllowedTypes('sortable', 'bool')
            ->setAllowedTypes('label', ['null', 'string'])
            ->setAllowedTypes('translation_domain', ['null', 'string'])
            ->setAllowedTypes('alignment', ['null', 'string'])
            ->setAllowedTypes('head_attr', 'array')
            ->setAllowedTypes('cell_attr', 'array')
            ->setAllowedValues('alignment', [null, 'left', 'center', 'right']);
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return null;
    }
}

<?php

namespace Ekyna\Component\Table\Extension\Core\Type\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Extension\Core\Source\ArrayAdapter;
use Ekyna\Component\Table\Filter\AbstractFilterType;
use Ekyna\Component\Table\Filter\FilterBuilderInterface;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilterView;
use Ekyna\Component\Table\View\AvailableFilterView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FilterType
 * @package Ekyna\Component\Table\Extension\Core\Type\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FilterType extends AbstractFilterType
{
    /**
     * @inheritDoc
     */
    public function buildFilter(FilterBuilderInterface $builder, array $options)
    {
        $builder
            ->setLabel($options['label'] ?: ucfirst($builder->getName()))
            ->setPosition($options['position'])
            ->setPropertyPath($options['property_path'] ?: $builder->getName());
    }

    /**
     * @inheritDoc
     */
    public function buildAvailableView(AvailableFilterView $view, FilterInterface $filter, array $options)
    {
        $name = $filter->getName();
        $tableName = $filter->getTable()->getName();

        $id = sprintf('%s_%s', $tableName, $name);
        $fullName = sprintf('%s[%s]', $tableName, $name);

        /*$blockName = $options['block_name'] ?: $name;
        $uniqueBlockPrefix = sprintf('%s_%s_available', $view->table->vars['unique_block_prefix'], $blockName);

        $blockPrefixes = [];
        for ($type = $filter->getConfig()->getType(); null !== $type; $type = $type->getParent()) {
            array_unshift($blockPrefixes, $type->getBlockPrefix());
        }
        $blockPrefixes[] = $uniqueBlockPrefix;

        $cacheKey = $uniqueBlockPrefix . '_' . $filter->getConfig()->getType()->getBlockPrefix() . '_available';*/

        $addFilterHref = $filter->getTable()->getParametersHelper()->getAddFilterHref($filter);

        $view->vars = array_replace($view->vars, [
            'id'                 => $id,
            'name'               => $name,
            'full_name'          => $fullName,
            'label'              => $filter->getLabel(),
            'translation_domain' => $options['translation_domain'],
            'attr'               => $options['available_attr'],
            'add_filter_href'    => $addFilterHref, // TODO href in attr
            //'block_prefixes'      => $blockPrefixes,
            //'unique_block_prefix' => $uniqueBlockPrefix,
            //'cache_key'           => $cacheKey,
            'block_prefix'       => $filter->getConfig()->getType()->getBlockPrefix(), // TODO remove
        ]);
    }

    /**
     * @inheritDoc
     */
    public function buildActiveView(ActiveFilterView $view, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        $name = $filter->getName();
        $tableName = $filter->getTable()->getName();

        $id = sprintf('%s_%s', $tableName, $name);
        $fullName = sprintf('%s[%s]', $tableName, $name);

        /*$blockName = $options['block_name'] ?: $name;
        $uniqueBlockPrefix = sprintf('%s_%s_active', $view->table->vars['unique_block_prefix'], $blockName);

        $blockPrefixes = [];
        for ($type = $filter->getConfig()->getType(); null !== $type; $type = $type->getParent()) {
            array_unshift($blockPrefixes, $type->getBlockPrefix());
        }
        $blockPrefixes[] = $uniqueBlockPrefix;

        $cacheKey = $uniqueBlockPrefix . '_' . $filter->getConfig()->getType()->getBlockPrefix() . '_active';*/

        $removeFilterHref = $filter->getTable()->getParametersHelper()->getRemoveFilterHref($activeFilter);

        $view->vars = array_replace($view->vars, [
            'id'                 => $id,
            'name'               => $name,
            'full_name'          => $fullName,
            'label'              => $filter->getLabel(),
            'translation_domain' => $options['translation_domain'],
            'attr'               => $options['active_attr'],
            'remove_filter_href' => $removeFilterHref, // TODO href in attr
            'operator'           => FilterOperator::getLabel($activeFilter->getOperator()),
            'value'              => $activeFilter->getValue(),
            //'block_prefixes'      => $blockPrefixes,
            //'unique_block_prefix' => $uniqueBlockPrefix,
            //'cache_key'           => $cacheKey,
            'block_prefix'       => $filter->getConfig()->getType()->getBlockPrefix(), // TODO remove
        ]);
    }

    /**
     * @inheritDoc
     */
    public function applyFilter(AdapterInterface $adapter, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        if (!$adapter instanceof ArrayAdapter) {
            return false;
        }

        $closure = $adapter->buildFilterClosure(
            $filter->getConfig()->getPropertyPath(),
            $activeFilter->getOperator(),
            $activeFilter->getValue()
        );

        $adapter->addFilterClosure($closure);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'position'           => 0,
                'property_path'      => null,
                'label'              => null,
                'translation_domain' => null,
                'available_attr'     => [],
                'active_attr'        => [],
                'block_name'         => null,
            ])
            ->setAllowedTypes('position', 'int')
            ->setAllowedTypes('property_path', ['null', 'string'])
            ->setAllowedTypes('label', ['null', 'string'])
            ->setAllowedTypes('translation_domain', ['null', 'string'])
            ->setAllowedTypes('available_attr', 'array')
            ->setAllowedTypes('active_attr', 'array')
            ->setAllowedTypes('block_name', ['null', 'string']);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'filter';
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return null;
    }
}

<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilter;
use Ekyna\Component\Table\View\AvailableFilter;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * AbstractFilterType
 */
abstract class AbstractFilterType implements FilterTypeInterface
{
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'name'          => null,
            'full_name'     => null,
            'type'          => $this->getName(),
            'label'         => function (Options $options) {
                return ucfirst($options['name']);
            },
            'property_path' => function (Options $options) {
                return $options['name'];
            },
        ));
        $resolver->setRequired(array('name', 'full_name', 'type', 'label', 'property_path'));
        $resolver->setAllowedTypes(array(
            'name'          => 'string',
            'full_name'     => 'string',
            'type'          => 'string',
            'label'         => 'string',
            'property_path' => 'string',
        ));
    }

    public function buildTableFilter(Table $table, $name, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options['name'] = $name;
        $options['full_name'] = sprintf('%s_%s', $table->getName(), $name);
        $resolvedOptions = $resolver->resolve($options);

        $table->addFilter($resolvedOptions);
    }

    public function buildAvailableFilter(AvailableFilter $filter, array $options)
    {
        $filter->setVars(array(
            'name'      => $options['name'],
            'full_name' => $options['full_name'],
            'label'     => $options['label'],
        ));
    }

    public function buildActiveFilters(TableView $view, array $datas)
    {
        $activeFilter = new ActiveFilter();
        $activeFilter->setVars(array(
            'full_name' => $datas['full_name'],
            'id'        => $datas['id'],
            //'label'     => sprintf('<strong>%s</strong> %s "%s"', $datas['label'], FilterOperator::getLabel($datas['operator']), $datas['value']),
            'field' => $datas['label'],
            'operator' => FilterOperator::getLabel($datas['operator']),
            'value' => $datas['value']
        ));
        $view->active_filters[] = $activeFilter;
    }
}
<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\ActiveFilter;
use Ekyna\Component\Table\View\AvailableFilter;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AbstractFilterType
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractFilterType implements FilterTypeInterface
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function buildTableFilter(TableConfig $config, $name, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options['name'] = $name;
        $options['full_name'] = sprintf('%s_%s', $config->getName(), $name);
        $resolvedOptions = $resolver->resolve($options);

        $config->addFilter($resolvedOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function buildAvailableFilter(AvailableFilter $filter, array $options)
    {
        $filter->setVars(array(
            'name'      => $options['name'],
            'full_name' => $options['full_name'],
            'label'     => $options['label'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildActiveFilters(TableView $view, array $datas)
    {
        $activeFilter = new ActiveFilter();
        $activeFilter->setVars(array(
            'full_name' => $datas['full_name'],
            'id'        => $datas['id'],
            'field'     => $datas['label'],
            'operator'  => FilterOperator::getLabel($datas['operator']),
            'value'     => $datas['value']
        ));
        $view->active_filters[] = $activeFilter;
    }
}

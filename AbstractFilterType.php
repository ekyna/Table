<?php

namespace Ekyna\Component\Table;

use Doctrine\ORM\QueryBuilder;
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
    static protected $filterCount = 0;

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
    public function buildActiveFilter(TableView $view, array $data, array $options)
    {
        $activeFilter = new ActiveFilter();
        $activeFilter->setVars(array(
            'full_name' => $data['full_name'],
            'id'        => $data['id'],
            'field'     => $data['label'],
            'operator'  => FilterOperator::getLabel($data['operator']),
            'value'     => $data['value']
        ));
        $view->active_filters[] = $activeFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function applyFilter(QueryBuilder $qb, array $data, array $options)
    {
        self::$filterCount++;
        $alias = $qb->getRootAliases()[0];
        $qb
            ->andWhere(FilterOperator::buildExpression(
                $alias.'.'.$data['property_path'],
                $data['operator'],
                ':filter_'.self::$filterCount
            ))
            ->setParameter(
                'filter_'.self::$filterCount,
                FilterOperator::buildParameter($data['operator'], $data['value'])
            )
        ;
    }
}

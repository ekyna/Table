<?php

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Filter\FilterBuilderInterface;
use Ekyna\Component\Table\Filter\FilterInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\View\ActiveFilterView;
use Ekyna\Component\Table\View\AvailableFilterView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractFilterTypeExtension
 * @package Ekyna\Component\Table\Extension
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractFilterTypeExtension implements FilterTypeExtensionInterface
{
    /**
     * @inheritDoc
     */
    public function buildFilter(FilterBuilderInterface $builder, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildAvailableFilterView(AvailableFilterView $view, FilterInterface $filter, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildActiveFilterView(ActiveFilterView $view, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildFilterForm(FormBuilderInterface $builder, FilterInterface $filter, array $options)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function applyFilter(AdapterInterface $adapter, FilterInterface $filter, ActiveFilter $activeFilter, array $options)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}

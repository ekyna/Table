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
 * Interface FilterTypeExtensionInterface
 * @package Ekyna\Component\Table\Extension
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface FilterTypeExtensionInterface
{
    /**
     * Builds the filter.
     *
     * This method is called after the extended type has built the filter to
     * further modify it.
     *
     * @see FilterTypeInterface::buildFilter()
     *
     * @param FilterBuilderInterface $builder The filter builder
     * @param array                  $options The options
     */
    public function buildFilter(FilterBuilderInterface $builder, array $options);

    /**
     * Builds the available filter view.
     *
     * @param AvailableFilterView $view
     * @param FilterInterface     $filter
     * @param array               $options
     */
    public function buildAvailableFilterView(AvailableFilterView $view, FilterInterface $filter, array $options);

    /**
     * Builds the active filter view.
     *
     * @param ActiveFilterView $view
     * @param FilterInterface  $filter
     * @param ActiveFilter     $activeFilter
     * @param array            $options
     */
    public function buildActiveFilterView(ActiveFilterView $view, FilterInterface $filter, ActiveFilter $activeFilter, array $options);

    /**
     * Builds the filter form.
     *
     * @param FormBuilderInterface $builder
     * @param FilterInterface      $filter
     * @param array                $options
     *
     * @return bool Whether the filter form has been built.
     */
    public function buildFilterForm(FormBuilderInterface $builder, FilterInterface $filter, array $options);

    /**
     * Applies the filter to the adapter.
     *
     * @param AdapterInterface $adapter
     * @param FilterInterface  $filter
     * @param ActiveFilter     $activeFilter
     * @param array            $options
     *
     * @return bool Whether the filter has been applied.
     */
    public function applyFilter(AdapterInterface $adapter, FilterInterface $filter, ActiveFilter $activeFilter, array $options);

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType();
}

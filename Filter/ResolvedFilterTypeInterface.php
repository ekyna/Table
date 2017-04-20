<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Extension\FilterTypeExtensionInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ResolvedFilterTypeInterface
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ResolvedFilterTypeInterface
{
    /**
     * Returns the prefix of the template block name for this type.
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string;

    /**
     * Returns the parent type.
     *
     * @return ResolvedFilterTypeInterface|null The parent type or null
     */
    public function getParent(): ?ResolvedFilterTypeInterface;

    /**
     * Returns the wrapped filter type.
     *
     * @return FilterTypeInterface The wrapped filter type
     */
    public function getInnerType(): FilterTypeInterface;

    /**
     * Returns the extensions of the wrapped filter type.
     *
     * @return FilterTypeExtensionInterface[] An array of {@link FilterTypeExtensionInterface} instances
     */
    public function getTypeExtensions(): array;

    /**
     * Creates a new filter builder for this type.
     *
     * @param FormFactoryInterface $factory The form factory
     * @param string               $name    The name for the builder
     * @param array                $options The builder options
     *
     * @return FilterBuilderInterface The created filter builder
     */
    public function createBuilder(
        FormFactoryInterface $factory,
        string $name,
        array $options = []
    ): FilterBuilderInterface;

    /**
     * Configures a filter builder for the type hierarchy.
     *
     * @param FilterBuilderInterface $builder The builder to configure
     * @param array                  $options The options used for the configuration
     */
    public function buildFilter(FilterBuilderInterface $builder, array $options): void;

    /**
     * Creates a new available filter view for a filter of this type.
     *
     * @param FilterInterface $filter The filter to create a head view for
     * @param View\TableView  $table  The table view
     *
     * @return View\AvailableFilterView The created available filter view
     */
    public function createAvailableView(FilterInterface $filter, View\TableView $table): View\AvailableFilterView;

    /**
     * Builds the available filter view.
     *
     * @param View\AvailableFilterView $view
     * @param FilterInterface          $filter
     * @param array                    $options
     */
    public function buildAvailableView(View\AvailableFilterView $view, FilterInterface $filter, array $options);

    /**
     * Creates a new active filter view for a filter of this type.
     *
     * @param FilterInterface $filter The filter to create a active view for
     * @param View\TableView  $table  The table view
     *
     * @return View\ActiveFilterView The created active filter view
     */
    public function createActiveView(FilterInterface $filter, View\TableView $table): View\ActiveFilterView;

    /**
     * Builds the active filter view.
     *
     * @param View\ActiveFilterView $view
     * @param FilterInterface       $filter
     * @param ActiveFilter          $activeFilter
     * @param array                 $options
     */
    public function buildActiveView(
        View\ActiveFilterView $view,
        FilterInterface $filter,
        ActiveFilter $activeFilter,
        array $options
    ): void;

    /**
     * Creates the filter form.
     *
     * @param FilterInterface $filter
     * @param array           $options
     *
     * @return FormInterface The filter form
     */
    public function createForm(FilterInterface $filter, array $options): FormInterface;

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
    public function applyFilter(
        AdapterInterface $adapter,
        FilterInterface $filter,
        ActiveFilter $activeFilter,
        array $options
    ): bool;

    /**
     * Returns the configured options resolver used for this type.
     *
     * @return OptionsResolver The options resolver
     */
    public function getOptionsResolver(): OptionsResolver;
}

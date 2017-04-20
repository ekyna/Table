<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\View\ActiveFilterView;
use Ekyna\Component\Table\View\AvailableFilterView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface FilterTypeInterface
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface FilterTypeInterface
{
    /**
     * Builds the filter.
     *
     * @param FilterBuilderInterface $builder
     * @param array                  $options
     */
    public function buildFilter(FilterBuilderInterface $builder, array $options): void;

    /**
     * Builds the available filter view.
     *
     * @param AvailableFilterView $view
     * @param FilterInterface     $filter
     * @param array               $options
     */
    public function buildAvailableView(AvailableFilterView $view, FilterInterface $filter, array $options): void;

    /**
     * Builds the active filter view.
     *
     * @param ActiveFilterView $view
     * @param FilterInterface  $filter
     * @param ActiveFilter     $activeFilter
     * @param array            $options
     */
    public function buildActiveView(
        ActiveFilterView $view,
        FilterInterface $filter,
        ActiveFilter $activeFilter,
        array $options
    ): void;

    /**
     * Builds the filter form.
     *
     * @param FormBuilderInterface $builder
     * @param FilterInterface      $filter
     * @param array                $options
     *
     * @return bool Whether the form has been built.
     */
    public function buildForm(FormBuilderInterface $builder, FilterInterface $filter, array $options): bool;

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
     * Configure the options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void;

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string;

    /**
     * Returns the name of the parent type.
     *
     * @return string|null The name of the parent type if any, null otherwise
     */
    public function getParent(): ?string;
}

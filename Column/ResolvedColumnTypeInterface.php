<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Column;

use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Extension\ColumnTypeExtensionInterface;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ResolvedColumnTypeInterface
 * @package Ekyna\Component\Table\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ResolvedColumnTypeInterface
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
     * @return ResolvedColumnTypeInterface|null The parent type or null
     */
    public function getParent(): ?ResolvedColumnTypeInterface;

    /**
     * Returns the wrapped column type.
     *
     * @return ColumnTypeInterface The wrapped column type
     */
    public function getInnerType(): ColumnTypeInterface;

    /**
     * Returns the extensions of the wrapped column type.
     *
     * @return ColumnTypeExtensionInterface[] An array of {@link ColumnTypeExtensionInterface} instances
     */
    public function getTypeExtensions(): array;

    /**
     * Creates a new column builder for this type.
     *
     * @param string $name    The name for the builder
     * @param array  $options The builder options
     *
     * @return ColumnBuilderInterface The created column builder
     */
    public function createBuilder(string $name, array $options = []): ColumnBuilderInterface;

    /**
     * Configures a column builder for the type hierarchy.
     *
     * @param ColumnBuilderInterface $builder The builder to configure
     * @param array                  $options The options used for the configuration
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $options): void;

    /**
     * Creates a new column head view for a column of this type.
     *
     * @param ColumnInterface $column The column to create a head view for
     * @param View\TableView  $table  The table view
     *
     * @return View\HeadView The created column head view
     */
    public function createHeadView(ColumnInterface $column, View\TableView $table): View\HeadView;

    /**
     * Configures a column head view for the type hierarchy.
     *
     * It is called before the children of the view are built.
     *
     * @param View\HeadView   $view    The column head view to configure
     * @param ColumnInterface $column  The column corresponding to the view
     * @param array           $options The options used for the configuration
     */
    public function buildHeadView(View\HeadView $view, ColumnInterface $column, array $options);

    /**
     * Creates a new column cell view for a column of this type.
     *
     * @param ColumnInterface $column The column to create a cell view for
     * @param View\RowView    $row    The row view
     *
     * @return View\CellView The created column cell view
     */
    public function createCellView(ColumnInterface $column, View\RowView $row): View\CellView;

    /**
     * Configures a column cell view for the type hierarchy.
     *
     * It is called before the children of the view are built.
     *
     * @param View\CellView   $view    The column cell view to configure
     * @param ColumnInterface $column  The column corresponding to the view
     * @param RowInterface    $row     The current row
     * @param array           $options The options used for the configuration
     */
    public function buildCellView(
        View\CellView $view,
        ColumnInterface $column,
        RowInterface $row,
        array $options
    ): void;

    /**
     * Configures the adapter.
     *
     * @param AdapterInterface $adapter
     * @param ColumnInterface  $column
     * @param array            $options
     */
    public function configureAdapter(
        AdapterInterface $adapter,
        ColumnInterface $column,
        array $options
    ): void;

    /**
     * Applies the sort to the adapter.
     *
     * @param AdapterInterface $adapter
     * @param ColumnInterface  $column
     * @param ActiveSort       $activeSort
     * @param array            $options
     *
     * @return bool Whether the sort has been applied.
     */
    public function applySort(
        AdapterInterface $adapter,
        ColumnInterface $column,
        ActiveSort $activeSort,
        array $options
    ): bool;

    /**
     * Returns the configured options resolver used for this type.
     *
     * @return OptionsResolver The options resolver
     */
    public function getOptionsResolver(): OptionsResolver;
}

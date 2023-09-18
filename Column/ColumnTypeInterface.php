<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Column;

use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Ekyna\Component\Table\View\HeadView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ColumnTypeInterface
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface ColumnTypeInterface
{
    /**
     * Builds the column.
     *
     * @param ColumnBuilderInterface $builder
     * @param array                  $options
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $options): void;

    /**
     * Builds the head view.
     *
     * @param HeadView        $view
     * @param ColumnInterface $column
     * @param array           $options
     */
    public function buildHeadView(HeadView $view, ColumnInterface $column, array $options): void;

    /**
     * Builds the cell view.
     *
     * @param CellView        $view
     * @param ColumnInterface $column
     * @param RowInterface    $row
     * @param array           $options
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options): void;

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
     * Configures the options.
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

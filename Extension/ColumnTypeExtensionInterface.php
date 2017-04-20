<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\Column\ColumnBuilderInterface;
use Ekyna\Component\Table\Column\ColumnInterface;
use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\View\CellView;
use Ekyna\Component\Table\View\HeadView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ColumnTypeExtensionInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ColumnTypeExtensionInterface
{
    /**
     * Builds the column.
     *
     * This method is called after the extended type has built the column to
     * further modify it.
     *
     * @see ColumnTypeInterface::buildColumn()
     *
     * @param ColumnBuilderInterface $builder The column builder
     * @param array                  $options The options
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $options);

    /**
     * Builds the column head view.
     *
     * This method is called after the extended type has built the view to
     * further modify it.
     *
     * @see ColumnTypeInterface::buildHeadView()
     *
     * @param HeadView        $view    The column head view
     * @param ColumnInterface $column  The column
     * @param array           $options The options
     */
    public function buildHeadView(HeadView $view, ColumnInterface $column, array $options);

    /**
     * Builds the column cell view.
     *
     * This method is called after the extended type has built the view to
     * further modify it.
     *
     * @see ColumnTypeInterface::buildView()
     *
     * @param CellView        $view    The column cell view
     * @param ColumnInterface $column  The column
     * @param RowInterface    $row     The current row
     * @param array           $options The options
     */
    public function buildCellView(CellView $view, ColumnInterface $column, RowInterface $row, array $options): void;


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
    public function applySort(AdapterInterface $adapter, ColumnInterface $column, ActiveSort $activeSort, array $options): bool;

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void;

    /**
     * Returns the names of the types being extended.
     *
     * @return string[] The names of the types being extended
     */
    public static function getExtendedTypes(): array;
}

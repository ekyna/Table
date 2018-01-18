<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Source\RowInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface TableTypeInterface
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface TableTypeInterface
{
    /**
     * Builds the table.
     *
     * @param TableBuilderInterface $builder
     * @param array                 $options
     */
    public function buildTable(TableBuilderInterface $builder, array $options);

    /**
     * Builds the table view.
     *
     * @param View\TableView $view
     * @param TableInterface $table
     * @param array          $options
     */
    public function buildView(View\TableView $view, TableInterface $table, array $options);

    /**
     * Builds the row view.
     *
     * @param View\RowView $view
     * @param RowInterface $row
     * @param array        $options
     */
    public function buildRowView(View\RowView $view, RowInterface $row, array $options);

    /**
     * Sets the default options.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Returns the name of the parent type.
     *
     * @return string|null The name of the parent type if any, null otherwise
     */
    public function getParent();
}

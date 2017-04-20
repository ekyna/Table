<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Extension;

use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\TableBuilderInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface TableTypeExtensionInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface TableTypeExtensionInterface
{
    /**
     * Builds the table.
     *
     * This method is called after the extended type has built the table to
     * further modify it.
     *
     * @see TableTypeInterface::buildTable()
     *
     * @param TableBuilderInterface $builder The table builder
     * @param array                 $options The options
     */
    public function buildTable(TableBuilderInterface $builder, array $options): void;

    /**
     * Builds the table view.
     *
     * This method is called after the extended type has built the view to
     * further modify it.
     *
     * @see TableTypeInterface::buildView()
     *
     * @param View\TableView $view    The view
     * @param TableInterface $table   The table
     * @param array          $options The options
     */
    public function buildView(View\TableView $view, TableInterface $table, array $options): void;

    /**
     * Builds the row view.
     *
     * This method is called after the extended type has built the view to
     * further modify it.
     *
     * @see TableTypeInterface::buildRowView()
     *
     * @param View\RowView $view    The view
     * @param RowInterface $row     The row
     * @param array        $options The options
     */
    public function buildRowView(View\RowView $view, RowInterface $row, array $options): void;

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

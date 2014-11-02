<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\View\Cell;
use Ekyna\Component\Table\View\Column;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Interface ColumnTypeInterface
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ColumnTypeInterface
{
    /**
     * Adds a column to the given table configuration.
     * 
     * @param TableConfig $table
     * @param string      $name
     * @param array       $options
     */
    public function buildTableColumn(TableConfig $table, $name, array $options = array());

    /**
     * Builds the view column.
     * 
     * @param Column $column
     * @param Table  $table
     * @param array  $options
     */
    public function buildViewColumn(Column $column, Table $table, array $options);

    /**
     * Builds the view cell.
     * 
     * @param Cell   $cell
     * @param Table  $table
     * @param array  $options
     */
    public function buildViewCell(Cell $cell, Table $table, array $options);

    /**
     * Configures the options.
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolverInterface $resolver);

    /**
     * Returns column type name.
     * 
     * @return string
     */
    public function getName();
}
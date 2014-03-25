<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\View\Cell;
use Ekyna\Component\Table\View\Column;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * ColumnTypeInterface
 */
interface ColumnTypeInterface
{
    /**
     * Adds a column to the given table (Called by TableBuilder via TableFactory)
     * 
     * @param Table  $table
     * @param string $name
     * @param array  $options
     */
    public function buildTableColumn(Table $table, $name, array $options = array());

    /**
     * Sets Column vars (Called by TableGenerator)
     * 
     * @param HeadCell       $cell
     * @param TableGenerator $generator
     * @param array          $options
     */
    public function buildViewColumn(Column $column, TableGenerator $generator, array $options);

    /**
     * Sets Cell vars (Called by TableGenerator)
     * 
     * @param BodyCell         $cell
     * @param PropertyAccessor $propertyAccessor
     * @param object           $entity
     * @param array            $options
     */
    public function buildViewCell(Cell $cell, PropertyAccessor $propertyAccessor, $entity, array $options);

    /**
     * Configure options
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolverInterface $resolver);

    /**
     * Returns column type name
     * 
     * @return string
     */
    public function getName();
}
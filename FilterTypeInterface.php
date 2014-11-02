<?php

namespace Ekyna\Component\Table;

use Doctrine\ORM\QueryBuilder;
use Ekyna\Component\Table\View\AvailableFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Interface FilterTypeInterface
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface FilterTypeInterface
{
    /**
     * Configure options
     *
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolverInterface $resolver);
    
    /**
     * Adds a filter to the given table
     * 
     * @param TableConfig $config
     * @param string      $name
     * @param array       $options
     */
    public function buildTableFilter(TableConfig $config, $name, array $options = array());

    /**
     * Sets available filter vars
     *
     * @param AvailableFilter $filter
     * @param array           $options
     */
    public function buildAvailableFilter(AvailableFilter $filter, array $options);

    /**
     * Adds active filter
     * and returns criteria
     * 
     * @param TableView $view
     * @param array     $datas
     * @param array     $options
     */
    public function buildActiveFilter(TableView $view, array $datas, array $options);

    /**
     * Applies the filter to the query builder.
     *
     * @param QueryBuilder $qb
     * @param array        $datas
     */
    public function applyFilter(QueryBuilder $qb, array $datas);

    /**
     * Creates the filter form widget
     *
     * @param FormBuilderInterface $form
     * @param array                $options
     */
    public function buildFilterFrom(FormBuilderInterface $form, array $options);

    /**
     * Returns filter operators
     * 
     * @return array
     */
    public function getOperators();

    /**
     * Returns filter type name
     *
     * @return string
     */
    public function getName();
}

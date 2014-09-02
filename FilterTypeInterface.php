<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\View\AvailableFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

interface FilterTypeInterface
{
    /**
     * Configure options
     *
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolverInterface $resolver);
    
    /**
     * Adds a filter to the given table (Called by TableBuilder via TableFactory)
     * 
     * @param TableConfig $config
     * @param string      $name
     * @param array       $options
     */
    public function buildTableFilter(TableConfig $config, $name, array $options = array());

    /**
     * Sets available filter vars (Called by TableGenerator)
     *
     * @param AvailableFilter $filter
     * @param array           $options
     */
    public function buildAvailableFilter(AvailableFilter $filter, array $options);

    /**
     * Adds active filter (Called by TableGenerator)
     * and returns criteria
     * 
     * @param TableView $view
     * @param array     $datas
     * 
     * @return array
     */
    public function buildActiveFilters(TableView $view, array $datas);

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
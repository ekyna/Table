<?php

namespace Ekyna\Component\Table;

/**
 * Interface FactoryInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface FactoryInterface
{
    /**
     * Returns a named table.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return TableInterface
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function createTable($name, $type, array $options = []);

    /**
     * Returns a named table builder.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return TableBuilderInterface
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function createTableBuilder($name, $type, array $options = []);

    /**
     * Returns a named column.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Column\ColumnInterface
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function createColumn($name, $type, array $options = []);

    /**
     * Returns a named column builder.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Column\ColumnBuilderInterface
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function createColumnBuilder($name, $type, array $options = []);

    /**
     * Returns a named filter.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Filter\FilterInterface
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function createFilter($name, $type, array $options = []);

    /**
     * Returns a named filter builder.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Filter\FilterBuilderInterface
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function createFilterBuilder($name, $type, array $options = []);

    /**
     * Returns a named action.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Action\ActionInterface
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function createAction($name, $type, array $options = []);

    /**
     * Returns a named action builder.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Action\ActionBuilderInterface
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function createActionBuilder($name, $type, array $options = []);

    /**
     * Returns the adapter.
     *
     * @param TableInterface $table
     *
     * @return Source\AdapterInterface
     */
    public function createAdapter(TableInterface $table);
}

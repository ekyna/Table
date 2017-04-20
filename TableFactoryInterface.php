<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * Interface TableFactoryInterface
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface TableFactoryInterface
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
     * @throws InvalidOptionsException
     */
    public function createTable(string $name, string $type, array $options = []): TableInterface;

    /**
     * Returns a named table builder.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return TableBuilderInterface
     *
     * @throws InvalidOptionsException
     */
    public function createTableBuilder(string $name, string $type, array $options = []): TableBuilderInterface;

    /**
     * Returns a named column.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Column\ColumnInterface
     *
     * @throws InvalidOptionsException
     */
    public function createColumn(string $name, string $type, array $options = []): Column\ColumnInterface;

    /**
     * Returns a named column builder.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Column\ColumnBuilderInterface
     *
     * @throws InvalidOptionsException
     */
    public function createColumnBuilder(string $name, string $type, array $options = []): Column\ColumnBuilderInterface;

    /**
     * Returns a named filter.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Filter\FilterInterface
     *
     * @throws InvalidOptionsException
     */
    public function createFilter(string $name, string $type, array $options = []): Filter\FilterInterface;

    /**
     * Returns a named filter builder.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Filter\FilterBuilderInterface
     *
     * @throws InvalidOptionsException
     */
    public function createFilterBuilder(string $name, string $type, array $options = []): Filter\FilterBuilderInterface;

    /**
     * Returns a named action.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Action\ActionInterface
     *
     * @throws InvalidOptionsException
     */
    public function createAction(string $name, string $type, array $options = []): Action\ActionInterface;

    /**
     * Returns a named action builder.
     *
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Action\ActionBuilderInterface
     *
     * @throws InvalidOptionsException
     */
    public function createActionBuilder(string $name, string $type, array $options = []): Action\ActionBuilderInterface;

    /**
     * Returns the adapter.
     *
     * @param TableInterface $table
     *
     * @return Source\AdapterInterface
     */
    public function createAdapter(TableInterface $table): Source\AdapterInterface;
}

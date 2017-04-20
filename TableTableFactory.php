<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class Factory
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class TableTableFactory implements TableFactoryInterface
{
    private RegistryInterface    $registry;
    private FormFactoryInterface $formFactory;


    /**
     * Constructor.
     *
     * @param RegistryInterface    $registry
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(RegistryInterface $registry, FormFactoryInterface $formFactory)
    {
        $this->registry = $registry;
        $this->formFactory = $formFactory;
    }

    /**
     * @inheritDoc
     */
    public function createTable(string $name, string $type, array $options = []): TableInterface
    {
        return $this->createTableBuilder($name, $type, $options)->getTable();
    }

    /**
     * @inheritDoc
     */
    public function createTableBuilder(string $name, string $type, array $options = []): TableBuilderInterface
    {
        $type = $this->registry->getTableType($type);

        $builder = $type->createBuilder($this, $name, $options);

        // Explicitly call buildTable() in order to be able to override either
        // createBuilder() or buildTable() in the resolved table type
        $type->buildTable($builder, $builder->getOptions());

        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function createColumn(string $name, string $type, array $options = []): Column\ColumnInterface
    {
        return $this->createColumnBuilder($name, $type, $options)->getColumn();
    }

    /**
     * @inheritDoc
     */
    public function createColumnBuilder(string $name, string $type, array $options = []): Column\ColumnBuilderInterface
    {
        $type = $this->registry->getColumnType($type);

        $builder = $type->createBuilder($name, $options);

        // Explicitly call buildColumn() in order to be able to override either
        // createBuilder() or buildColumn() in the resolved table type
        $type->buildColumn($builder, $builder->getOptions());

        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function createFilter(string $name, string $type, array $options = []): Filter\FilterInterface
    {
        return $this->createFilterBuilder($name, $type, $options)->getFilter();
    }

    /**
     * @inheritDoc
     */
    public function createFilterBuilder(string $name, string $type, array $options = []): Filter\FilterBuilderInterface
    {
        $type = $this->registry->getFilterType($type);

        $builder = $type->createBuilder($this->formFactory, $name, $options);

        // Explicitly call buildFilter() in order to be able to override either
        // createBuilder() or buildFilter() in the resolved table type
        $type->buildFilter($builder, $builder->getOptions());

        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function createAction(string $name, string $type, array $options = []): Action\ActionInterface
    {
        return $this->createActionBuilder($name, $type, $options)->getAction();
    }

    /**
     * @inheritDoc
     */
    public function createActionBuilder(string $name, string $type, array $options = []): Action\ActionBuilderInterface
    {
        $type = $this->registry->getActionType($type);

        $builder = $type->createBuilder($name, $options);

        // Explicitly call buildFilter() in order to be able to override either
        // createBuilder() or buildFilter() in the resolved table type
        $type->buildAction($builder, $builder->getOptions());

        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function createAdapter(TableInterface $table): Source\AdapterInterface
    {
        $source = $table->getConfig()->getSource();

        $factory = $this->registry->getAdapterFactory($source::getFactory());

        return $factory->createAdapter($table);
    }
}

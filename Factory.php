<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Extension\Core\Type;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class Factory
 * @package Ekyna\Component\Table
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Factory implements FactoryInterface
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;


    /**
     * Constructor.
     *
     * @param RegistryInterface $registry
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
    public function createTable($name, $type = Type\TableType::class, array $options = [])
    {
        return $this->createTableBuilder($name, $type, $options)->getTable();
    }

    /**
     * @inheritDoc
     */
    public function createTableBuilder($name, $type = Type\TableType::class, array $options = [])
    {
        /*if (null !== $data && !array_key_exists('data', $options)) {
            $options['data'] = $data;
        }*/

        if (!is_string($type)) {
            throw new Exception\UnexpectedTypeException($type, 'string');
        }

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
    public function createColumn($name, $type = Type\Column\ColumnType::class, array $options = [])
    {
        return $this->createColumnBuilder($name, $type, $options)->getColumn();
    }

    /**
     * @inheritDoc
     */
    public function createColumnBuilder($name, $type = Type\Column\ColumnType::class, array $options = [])
    {
        if (!is_string($type)) {
            throw new Exception\UnexpectedTypeException($type, 'string');
        }

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
    public function createFilter($name, $type = Type\Filter\FilterType::class, array $options = [])
    {
        return $this->createFilterBuilder($name, $type, $options)->getFilter();
    }

    /**
     * @inheritDoc
     */
    public function createFilterBuilder($name, $type = Type\Filter\FilterType::class, array $options = [])
    {
        if (!is_string($type)) {
            throw new Exception\UnexpectedTypeException($type, 'string');
        }

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
    public function createAction($name, $type = Type\Action\ActionType::class, array $options = [])
    {
        return $this->createActionBuilder($name, $options)->getAction();
    }

    /**
     * @inheritDoc
     */
    public function createActionBuilder($name, $type = Type\Action\ActionType::class, array $options = [])
    {
        if (!is_string($type)) {
            throw new Exception\UnexpectedTypeException($type, 'string');
        }

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
    public function createAdapter(TableInterface $table)
    {
        $source = $table->getConfig()->getSource();

        $factory = $this->registry->getAdapterFactory($source->getFactory());

        return $factory->createAdapter($table);
    }
}

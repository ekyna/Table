<?php

namespace Ekyna\Component\Table;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactory;

/**
 * TableFactory
 */
class TableFactory
{
    /**
     * @var TableRegistryInterface
     */
    private $registry;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $entityManager;

    /**
     * Initialize the TableFactory
     */
    public function __construct(TableRegistryInterface $registry, FormFactory $formFactory, ObjectManager $entityManager)
    {
        $this->registry = $registry;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * Returns the entityManager.
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Returns the formFactory.
     *
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    /**
     * Returns the registry.
     *
     * @return \Ekyna\Component\Table\TableRegistryInterface
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * Creates a column for given table
     *
     * @param TableConfig $config
     * @param string      $name
     * @param string      $type
     * @param array       $options
     */
    public function createColumn(TableConfig $config, $name, $type = null, array $options = array())
    {
        if(null === $type) {
            $type = 'text';
        }
        $columnType = $this->registry->getColumnType($type);
        $columnType->buildTableColumn($config, $name, $options);
    }

    /**
     * Creates a filter for given table
     *
     * @param TableConfig $config
     * @param string      $name
     * @param string      $type
     * @param array       $options
     */
    public function createFilter(TableConfig $config, $name, $type = null, array $options = array())
    {
        if(null === $type) {
            $type = 'text';
        }
        $filterType = $this->registry->getFilterType($type);
        $filterType->buildTableFilter($config, $name, $options);
    }

    /**
     * Returns a table builder
     *
     * @param TableTypeInterface|string $type
     * @param array $options
     *
     * @return \Ekyna\Component\Table\TableBuilder
     */
    public function createBuilder($type = 'table', array $options = array())
    {
        $type = $type instanceof TableTypeInterface ? $type : $this->getTableType($type);

        $builder = new TableBuilder($type);

        $builder
            ->setFactory($this)
            ->setOptions($options)
        ;
        $type->buildTable($builder);

        return $builder;
    }

    /**
     * Returns a table type by name.
     * 
     * @param string $name The name of the type
     *
     * @return TableTypeInterface The type
     */
    public function getTableType($name)
    {
        return $this->registry->getTableType($name);
    }

    /**
     * Returns a column type by name.
     * 
     * @param string $name The name of the type
     *
     * @return ColumnTypeInterface The type
     */
    public function getColumnType($name)
    {
        return $this->registry->getColumnType($name);
    }

    /**
     * Returns a filter type by name.
     * 
     * @param string $name The name of the type
     *
     * @return FilterTypeInterface The type
     */
    public function getFilterType($name)
    {
        return $this->registry->getFilterType($name);
    }
}
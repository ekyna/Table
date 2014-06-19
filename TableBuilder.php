<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Ekyna\Component\Table\Exception\RuntimeException;

/**
 * TableBuilder
 */
class TableBuilder implements TableBuilderInterface
{
    /**
     * @var \Ekyna\Component\Table\TableFactory
     */
    private $factory;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $defaultSort;

    /**
     * @var \Closure
     */
    private $customizeQb;
    
    /**
     * @var integer
     */
    private $maxPerPage = 15;

    /**
     * @param TableFactory       $factory
     * @param TableTypeInterface $type
     * @param string             $entityClass
     */
    public function __construct(TableFactory $factory, TableTypeInterface $type, $entityClass = null)
    {
        $this->factory = $factory;

        $this->columns = array();
        $this->filters = array();

        $entityClass = null !== $entityClass ? $entityClass : $type->getEntityClass();

        $this->setEntityClass($entityClass);
        $type->buildTable($this);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSort($property, $dir = 'ASC')
    {
        $this->defaultSort = array($property, $dir);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomizeQueryBuilder(\Closure $closure = null)
    {
        $this->customizeQb = $closure;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxPerPage($max)
    {
        $this->maxPerPage = $max;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setTableName($name)
    {
        $this->tableName = $name;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityClass($class)
    {
        if(null === $class || !class_exists($class)) {
            throw new RuntimeException(sprintf('"%s" can not be found.', $class));
        }
        $this->entityClass = $class;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addColumn($name, $type = null, array $options = array())
    {
        if(array_key_exists($name, $this->columns)) {
            throw new InvalidArgumentException(sprintf('Column "%s" is allready defined.', $name));
        }
        $this->columns[$name] = array($type, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter($name, $type = null, array $options = array())
    {
        if(array_key_exists($name, $this->filters)) {
            throw new InvalidArgumentException(sprintf('Filter "%s" is allready defined.', $name));
        }
        $this->filters[$name] = array($type, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTable($name = null)
    {
        $tableName = $name ?: $this->tableName;
        if(null === $tableName) { 
            $tableName = substr($this->entityClass, strrpos($this->entityClass, '\\') + 1);
        }
        $tableName = preg_replace('/\W/', '_', strtolower($tableName));

        $table = new Table($tableName, $this->entityClass);

        foreach($this->columns as $name => $definition) {
            list($type, $options) = $definition;
            $this->factory->createColumn($table, $name, $type, $options);
        }

        foreach($this->filters as $name => $definition) {
            list($type, $options) = $definition;
            $this->factory->createFilter($table, $name, $type, $options);
        }

        $table
            ->setMaxPerPage($this->maxPerPage)
            ->setDefaultSort($this->defaultSort)
            ->setCustomizeQueryBuilder($this->customizeQb)
        ;

        return $table;
    }
}
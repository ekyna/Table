<?php

namespace Ekyna\Component\Table;

/**
 * Table
 */
class Table
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var array
     */
    protected $defaultSort;

    /**
     * @var \Closure
     */
    protected $customizeQb;

    /**
     * @var integer
     */
    protected $maxPerPage = 15;

    /**
     * @param string $name
     * @param string $entityClass
     */
    public function __construct($name, $entityClass)
    {
        $this->name = $name;
        $this->entityClass = $entityClass;

        $this->columns = array();
        $this->filters = array();
    }

    public function setDefaultSort(array $sort = null)
    {
        $this->defaultSort = $sort;
        return $this;
    }

    public function getDefaultSort()
    {
        return $this->defaultSort;
    }

    public function setCustomizeQueryBuilder(\Closure $closure = null)
    {
        $this->customizeQb = $closure;
        return $this;
    }

    public function getCustomizeQueryBuilder()
    {
        return $this->customizeQb;
    }

    public function setMaxPerPage($max)
    {
        $this->maxPerPage = $max;
        return $this;
    }

    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    /**
     * Returns table name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns entity class
     * 
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Adds a column
     *
     * @param array $resolvedOptions
     *
     * @return \Ekyna\Component\Table\Table
     */
    public function addColumn(array $resolvedOptions)
    {
        $this->columns[] = $resolvedOptions;
        return $this;
    }

    /**
     * Returns columns
     * 
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Returns whether the table has columns
     * 
     * @return array
     */
    public function hasColumns()
    {
        return 0 < count($this->columns);
    }

    /**
     * Adds a filter
     *
     * @param array $resolvedOptions
     *
     * @return \Ekyna\Component\Table\Table
     */
    public function addFilter(array $resolvedOptions)
    {
        $this->filters[] = $resolvedOptions;
        return $this;
    }

    /**
     * Returns filters
     * 
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Finds filterOptions by fullname
     *  
     * @param string $fullName
     * @return array|NULL
     */
    public function findFilterByFullName($fullName)
    {
        foreach($this->filters as $options) {
            if($fullName === $options['full_name']) {
                return $options;
            }
        }
        return null;
    }
}
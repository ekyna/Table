<?php

namespace Ekyna\Component\Table;

/**
 * Class TableConfig
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TableConfig
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $dataClass;

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
     * @var integer
     */
    protected $nbPerPage;

    /**
     * @var callable
     */
    protected $customizeQb;

    /**
     * Constructor.
     */
    public function __construct($name)
    {
        $this->name = preg_replace('#[^a-zA-Z_]#', '_', $name);

        $this->columns = array();
        $this->filters = array();
    }

    /**
     * Sets the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the dataClass.
     *
     * @param string $dataClass
     *
     * @return TableConfig
     */
    public function setDataClass($dataClass)
    {
        $this->dataClass = $dataClass;
        return $this;
    }

    /**
     * Returns the dataClass.
     *
     * @return string
     */
    public function getDataClass()
    {
        return $this->dataClass;
    }

    /**
     * Adds a column.
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
     * Returns the columns.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Returns whether the table has columns or not.
     *
     * @return array
     */
    public function hasColumns()
    {
        return 0 < count($this->columns);
    }

    /**
     * Adds a filter.
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
     * Returns the filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Finds filter by his full name.
     *
     * @param string $fullName
     * @return array|NULL
     */
    public function findFilterByFullName($fullName)
    {
        foreach ($this->filters as $options) {
            if ($fullName === $options['full_name']) {
                return $options;
            }
        }
        return null;
    }

    /**
     * Sets the default sort.
     *
     * @param array $defaultSort
     * @return TableConfig
     */
    public function setDefaultSort(array $defaultSort = null)
    {
        $this->defaultSort = $defaultSort;
        return $this;
    }

    /**
     * Returns the default sort.
     *
     * @return array
     */
    public function getDefaultSort()
    {
        return $this->defaultSort;
    }

    /**
     * Sets the nbPerPage.
     *
     * @param int $nbPerPage
     * @return TableConfig
     */
    public function setNbPerPage($nbPerPage)
    {
        $this->nbPerPage = $nbPerPage;
        return $this;
    }

    /**
     * Returns the nbPerPage.
     *
     * @return int
     */
    public function getNbPerPage()
    {
        return $this->nbPerPage;
    }

    /**
     * Sets the customizeQb.
     *
     * @param callable $customizeQb
     * @return TableConfig
     */
    public function setCustomizeQb($customizeQb)
    {
        $this->customizeQb = $customizeQb;
        return $this;
    }

    /**
     * Returns the customizeQb.
     *
     * @return callable
     */
    public function getCustomizeQb()
    {
        return $this->customizeQb;
    }
}

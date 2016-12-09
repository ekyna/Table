<?php

namespace Ekyna\Component\Table;

/**
 * Class TableConfig
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
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
    protected $defaultSorts;

    /**
     * @var integer
     */
    protected $maxPerPage;

    /**
     * @var callable
     */
    protected $customizeQb;

    /**
     * @var boolean
     */
    protected $selector = false;

    /**
     * @var array
     */
    protected $selectorConfig;

    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = preg_replace('#[^a-zA-Z_]#', '_', $name);

        $this->defaultSorts = [];

        $this->columns = [];
        $this->filters = [];
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
     * @return TableConfig
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
     * @return bool
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
     * @return TableConfig
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
     *
     * @return array|null
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
     * @param array $defaultSorts
     *
     * @return TableConfig
     */
    public function setDefaultSorts(array $defaultSorts = [])
    {
        $this->defaultSorts = $defaultSorts;

        return $this;
    }

    /**
     * Returns the default sort.
     *
     * @return array
     */
    public function getDefaultSorts()
    {
        return $this->defaultSorts;
    }

    /**
     * Sets the maxPerPage.
     *
     * @param int $maxPerPage
     *
     * @return TableConfig
     */
    public function setMaxPerPage($maxPerPage)
    {
        $this->maxPerPage = $maxPerPage;

        return $this;
    }

    /**
     * Returns the maxPerPage.
     *
     * @return int
     */
    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    /**
     * Sets the customizeQb.
     *
     * @param callable $customizeQb
     *
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

    /**
     * Sets the selector.
     *
     * @param boolean $selector
     *
     * @return TableConfig
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;

        return $this;
    }

    /**
     * Returns the selector.
     *
     * @return boolean
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * Sets the selectorConfig.
     *
     * @param array $selectorConfig
     *
     * @return TableConfig
     */
    public function setSelectorConfig(array $selectorConfig = null)
    {
        $this->selectorConfig = $selectorConfig;

        return $this;
    }

    /**
     * Returns the selectorConfig.
     *
     * @return array
     */
    public function getSelectorConfig()
    {
        return $this->selectorConfig;
    }

    /**
     * Sorts the columns and filers.
     *
     * @return TableConfig
     */
    public function sortElements()
    {
        usort($this->columns, function ($a, $b) {
            if ($a['position'] == $b['position']) {
                return 0;
            }

            return $a['position'] > $b['position'] ? 1 : -1;
        });

        usort($this->filters, function ($a, $b) {
            if ($a['position'] == $b['position']) {
                return 0;
            }

            return $a['position'] > $b['position'] ? 1 : -1;
        });

        return $this;
    }
}

<?php

namespace Ekyna\Component\Table\Context;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormInterface;

/**
 * Class Context
 * @package Ekyna\Component\Table\Context
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Context implements ContextInterface
{
    /**
     * @var int
     */
    private $maxPerPage;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var array|string[]
     */
    private $visibleColumns;

    /**
     * @var ActiveSort|null
     */
    private $activeSort;

    /**
     * @var ActiveFilter[]
     */
    private $activeFilters;

    /**
     * @var array
     */
    private $selectedIdentifiers;

    /**
     * @var FormInterface
     */
    private $filterForm;

    /**
     * @var bool
     */
    private $all;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $format;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * @inheritDoc
     */
    public function reset()
    {
        $this->maxPerPage = null;
        $this->currentPage = 1;
        $this->visibleColumns = [];
        $this->activeSort = null;
        $this->activeFilters = [];
        $this->selectedIdentifiers = [];

        $this->filterForm = null;

        $this->all = false;
        $this->action = null;
        $this->format = null;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isDefault()
    {
        return (
            is_null($this->maxPerPage) &&
            is_null($this->currentPage) &&
            is_null($this->activeSort) &&
            empty($this->visibleColumns) &&
            empty($this->activeFilters) &&
            empty($this->selectedIdentifiers)
        );
    }

    /**
     * @inheritDoc
     */
    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    /**
     * @inheritDoc
     */
    public function setMaxPerPage($maxPerPage)
    {
        $this->maxPerPage = (int)$maxPerPage;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @inheritDoc
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = (int)$currentPage;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getVisibleColumns()
    {
        return $this->visibleColumns;
    }

    /**
     * @inheritDoc
     */
    public function setVisibleColumns(array $names)
    {
        // TODO compare size with default and if equals, set empty

        $this->visibleColumns = $names;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActiveSort()
    {
        return $this->activeSort;
    }

    /**
     * @inheritDoc
     */
    public function setActiveSort(ActiveSort $sort = null)
    {
        $this->activeSort = $sort;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActiveFilters()
    {
        return $this->activeFilters;
    }

    /**
     * @inheritDoc
     */
    public function hasActiveFilter($id)
    {
        return isset($this->activeFilters[$id]);
    }

    /**
     * @inheritDoc
     */
    public function addActiveFilter(ActiveFilter $filter)
    {
        if (isset($this->activeFilters[$filter->getId()])) {
            throw new InvalidArgumentException("This active filter is already registered.");
        }

        $this->activeFilters[$filter->getId()] = $filter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function removeActiveFilter($id)
    {
        if ($this->hasActiveFilter($id)) {
            unset($this->activeFilters[$id]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSelectedIdentifiers()
    {
        return $this->selectedIdentifiers;
    }

    /**
     * @inheritDoc
     */
    public function setSelectedIdentifiers(array $identifiers)
    {
        $this->selectedIdentifiers = $identifiers;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilterForm()
    {
        return $this->filterForm;
    }

    /**
     * @inheritDoc
     */
    public function setFilterForm(FormInterface $filterForm)
    {
        $this->filterForm = $filterForm;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * @inheritDoc
     */
    public function setAll($all)
    {
        $this->all = (bool)$all;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @inheritDoc
     */
    public function setAction($name)
    {
        $this->action = $name;
    }

    /**
     * @inheritDoc
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @inheritDoc
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasSelection()
    {
        return $this->all || !empty($this->selectedIdentifiers);
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $filters = [];

        foreach ($this->activeFilters as $filter) {
            $filters[] = $filter->toArray();
        }

        $data = [
            $this->maxPerPage,
            $this->currentPage,
            $this->visibleColumns,
            $this->activeSort ? $this->activeSort->toArray() : null,
            $filters,
            $this->selectedIdentifiers
        ];

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function fromArray(array $data)
    {
        if (6 != count($data)) {
            throw new InvalidArgumentException("Expected 6 length array.");
        }

        $this->reset();

        $this->setMaxPerPage($data[0]);
        $this->setCurrentPage($data[1]);
        $this->setVisibleColumns($data[2]);

        if (null !== $data[3]) {
            $this->setActiveSort(ActiveSort::createFromArray($data[3]));
        }
        if (is_array($data[4])) {
            foreach ($data[4] as $filter) {
                $this->addActiveFilter(ActiveFilter::createFromArray($filter));
            }
        }

        $this->setSelectedIdentifiers($data[5]);
    }
}

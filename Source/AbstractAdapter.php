<?php

namespace Ekyna\Component\Table\Source;

use Ekyna\Component\Table\Context\ContextInterface;
use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\TableInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class AbstractAdapter
 * @package Ekyna\Component\Table\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    protected $propertyAccessor;

    /**
     * @var TableInterface
     */
    protected $table;


    /**
     * Constructor.
     *
     * @param TableInterface $table
     */
    public function __construct(TableInterface $table)
    {
        $this->validateSource($table->getConfig()->getSource());

        $this->table = $table;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        $this->reset();
    }

    /**
     * @inheritDoc
     */
    public function getGrid(ContextInterface $context)
    {
        $this->reset();

        // Pre initialize
        $this->preInitialize($context);

        // Filtering
        $this->initializeFiltering($context);

        // Sorting
        $this->initializeSorting($context);

        // Post initialize
        $this->postInitialize($context);

        $pager = new Pagerfanta($this->getPagerAdapter());
        $pager
            ->setMaxPerPage($context->getMaxPerPage())
            ->setCurrentPage($context->getCurrentPage())
            ->setNormalizeOutOfRangePages(true);

        $grid = new Grid($pager);

        $data = $pager->getCurrentPageResults();

        // Build data rows
        foreach ($data as $index => $datum) {
            $grid->addRow($this->createRow($index, $datum));
        }

        return $grid;
    }

    /**
     * @inheritDoc
     */
    public function getSelection(ContextInterface $context)
    {
        $this->reset();

        // Pre initialize
        $this->preInitialize($context);

        if ($context->getAll()) {
            // Filtering
            $this->initializeFiltering($context);
        } elseif (!empty($context->getSelectedIdentifiers())) {
            // Selection
            $this->initializeSelection($context);
        } else {
            return [];
        }

        // Sorting
        $this->initializeSorting($context);

        // Post initialize
        $this->postInitialize($context);

        return $this->getSelectedRows();
    }

    /**
     * Called before filtering and sorting are initialized.
     *
     * @param ContextInterface $context
     */
    protected function preInitialize(ContextInterface $context)
    {
    }

    /**
     * Initializes filtering by applying all active filters through the related filter types.
     *
     * @param ContextInterface $context
     */
    protected function initializeFiltering(ContextInterface $context)
    {
        $activeFilters = $context->getActiveFilters();

        foreach ($activeFilters as $activeFilter) {
            $filterName = $activeFilter->getFilterName();
            if ($this->table->hasFilter($filterName)) {
                $this->table->getFilter($filterName)->applyFilter($this, $activeFilter);
            }
        }
    }

    /**
     * Initializes sorting by applying all active sorts through the related column types.
     *
     * @param ContextInterface $context
     */
    protected function initializeSorting(ContextInterface $context)
    {
        if (null !== $activeSort = $context->getActiveSort()) {
            $columnName = $activeSort->getColumnName();
            if ($this->table->hasColumn($columnName)) {
                $this->table->getColumn($columnName)->applySort($this, $activeSort);
            }
        }
    }

    /**
     * Initializes selection by filtering from identifiers.
     *
     * @param ContextInterface $context
     */
    abstract protected function initializeSelection(ContextInterface $context);

    /**
     * Called after filtering and sorting are initialized.
     *
     * @param ContextInterface $context
     */
    protected function postInitialize(ContextInterface $context)
    {
    }

    /**
     * Returns the selected rows.
     *
     * @return Row[]
     */
    abstract protected function getSelectedRows();

    /**
     * Returns the pager adapter.
     *
     * @return \Pagerfanta\Adapter\AdapterInterface
     */
    abstract protected function getPagerAdapter();

    /**
     * Validates the source.
     *
     * @param SourceInterface $source
     *
     * @throws Exception\InvalidArgumentException
     */
    abstract protected function validateSource(SourceInterface $source);

    /**
     * Returns the source.
     *
     * @return SourceInterface
     */
    protected function getSource()
    {
        return $this->table->getConfig()->getSource();
    }

    /**
     * Resets the adapter.
     */
    protected function reset()
    {
    }

    /**
     * Creates the data row.
     *
     * @param string $identifier
     * @param object $data
     *
     * @return RowInterface
     */
    protected function createRow($identifier, $data)
    {
        return new Row((string)$identifier, $data, $this->propertyAccessor);
    }
}

<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Source;

use Ekyna\Component\Table\Context\ContextInterface;
use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\TableInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\AdapterInterface as PagerfantaAdapter;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class AbstractAdapter
 * @package Ekyna\Component\Table\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractAdapter implements AdapterInterface
{
    protected TableInterface $table;
    protected PropertyAccessorInterface $propertyAccessor;


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
    public function getGrid(ContextInterface $context): Grid
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
            ->setNormalizeOutOfRangePages(true)
            ->setMaxPerPage($context->getMaxPerPage())
            ->setCurrentPage($context->getCurrentPage());

        $grid = new Grid($pager);

        $data = $pager->getCurrentPageResults();

        // Build data rows
        foreach ($data as $index => $datum) {
            $grid->addRow($this->createRow((string)$index, $datum));
        }

        return $grid;
    }

    /**
     * @inheritDoc
     */
    public function getSelection(ContextInterface $context): array
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
    protected function preInitialize(ContextInterface $context): void
    {
    }

    /**
     * Initializes filtering by applying all active filters through the related filter types.
     *
     * @param ContextInterface $context
     */
    protected function initializeFiltering(ContextInterface $context): void
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
    protected function initializeSorting(ContextInterface $context): void
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
    abstract protected function initializeSelection(ContextInterface $context): void;

    /**
     * Called after filtering and sorting are initialized.
     *
     * @param ContextInterface $context
     */
    protected function postInitialize(ContextInterface $context): void
    {
    }

    /**
     * Returns the selected rows.
     *
     * @return RowInterface[]
     */
    abstract protected function getSelectedRows(): array;

    /**
     * Returns the pager adapter.
     *
     * @return PagerfantaAdapter
     */
    abstract protected function getPagerAdapter(): PagerfantaAdapter;

    /**
     * Validates the source.
     *
     * @param SourceInterface $source
     *
     * @throws Exception\InvalidArgumentException
     */
    abstract protected function validateSource(SourceInterface $source): void;

    /**
     * Returns the source.
     *
     * @return SourceInterface
     */
    protected function getSource(): SourceInterface
    {
        return $this->table->getConfig()->getSource();
    }

    /**
     * Resets the adapter.
     */
    protected function reset(): void
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
    protected function createRow(string $identifier, object $data): RowInterface
    {
        return new Row($identifier, $data, $this->propertyAccessor);
    }
}

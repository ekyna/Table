<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Context;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormInterface;

use function count;
use function is_array;
use function is_null;

/**
 * Class Context
 * @package Ekyna\Component\Table\Context
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class Context implements ContextInterface
{
    private int         $maxPerPage;
    private int         $currentPage;
    private array       $visibleColumns;
    private ?ActiveSort $activeSort;
    /** @var ActiveFilter[] */
    private array          $activeFilters;
    private array          $selectedIdentifiers;
    private string         $filterLabel;
    private ?FormInterface $filterForm   = null;
    private bool           $all;
    private ?string        $action       = null;
    private ?string        $format       = null;


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
    public function reset(): ContextInterface
    {
        $this->maxPerPage = 15;
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
     * @inheritDoc
     */
    public function isDefault(): bool
    {
        return (
            (15 === $this->maxPerPage)
            && (1 === $this->currentPage)
            && is_null($this->activeSort)
            && empty($this->visibleColumns)
            && empty($this->activeFilters)
            && empty($this->selectedIdentifiers)
        );
    }

    /**
     * @inheritDoc
     */
    public function getMaxPerPage(): int
    {
        return $this->maxPerPage;
    }

    /**
     * @inheritDoc
     */
    public function setMaxPerPage(int $max): ContextInterface
    {
        $this->maxPerPage = $max;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @inheritDoc
     */
    public function setCurrentPage(int $num): ContextInterface
    {
        $this->currentPage = $num;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getVisibleColumns(): array
    {
        return $this->visibleColumns;
    }

    /**
     * @inheritDoc
     */
    public function setVisibleColumns(array $names): ContextInterface
    {
        // TODO compare size with default and if equals, set empty

        $this->visibleColumns = $names;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActiveSort(): ?ActiveSort
    {
        return $this->activeSort;
    }

    /**
     * @inheritDoc
     */
    public function setActiveSort(ActiveSort $sort = null): ContextInterface
    {
        $this->activeSort = $sort;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActiveFilters(): array
    {
        return $this->activeFilters;
    }

    /**
     * @inheritDoc
     */
    public function hasActiveFilter(string $id): bool
    {
        return isset($this->activeFilters[$id]);
    }

    /**
     * @inheritDoc
     */
    public function addActiveFilter(ActiveFilter $filter): ContextInterface
    {
        if (isset($this->activeFilters[$filter->getId()])) {
            throw new InvalidArgumentException('This active filter is already registered.');
        }

        $this->activeFilters[$filter->getId()] = $filter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function removeActiveFilter(string $id): ContextInterface
    {
        if ($this->hasActiveFilter($id)) {
            unset($this->activeFilters[$id]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSelectedIdentifiers(): array
    {
        return $this->selectedIdentifiers;
    }

    /**
     * @inheritDoc
     */
    public function setSelectedIdentifiers(array $identifiers): ContextInterface
    {
        $this->selectedIdentifiers = $identifiers;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilterLabel(): string
    {
        return $this->filterLabel;
    }

    /**
     * @inheritDoc
     */
    public function setFilterLabel(string $label): ContextInterface
    {
        $this->filterLabel = $label;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilterForm(): ?FormInterface
    {
        return $this->filterForm;
    }

    /**
     * @inheritDoc
     */
    public function setFilterForm(?FormInterface $form): ContextInterface
    {
        $this->filterForm = $form;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): bool
    {
        return $this->all;
    }

    /**
     * @inheritDoc
     */
    public function setAll(bool $all): ContextInterface
    {
        $this->all = $all;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @inheritDoc
     */
    public function setAction(string $name): ContextInterface
    {
        $this->action = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @inheritDoc
     */
    public function setFormat(string $format): ContextInterface
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasSelection(): bool
    {
        return $this->all || !empty($this->selectedIdentifiers);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $filters = [];

        foreach ($this->activeFilters as $filter) {
            $filters[] = $filter->toArray();
        }

        return [
            $this->maxPerPage,
            $this->currentPage,
            $this->visibleColumns,
            $this->activeSort ? $this->activeSort->toArray() : null,
            $filters,
            $this->selectedIdentifiers,
        ];
    }

    /**
     * @inheritDoc
     */
    public function fromArray(array $data): void
    {
        if (6 != count($data)) {
            throw new InvalidArgumentException('Expected 6 length array.');
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

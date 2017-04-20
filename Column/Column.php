<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Column;

use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\Source\RowInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\Util\ColumnSort;
use Ekyna\Component\Table\View;

/**
 * Class Column
 * @package Ekyna\Component\Table\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class Column implements ColumnInterface
{
    private ColumnConfigInterface $config;
    private ?TableInterface       $table = null;
    private string                $sortDirection;


    /**
     * Constructor.
     *
     * @param ColumnConfigInterface $config
     */
    public function __construct(ColumnConfigInterface $config)
    {
        $this->config = $config;

        $this->sortDirection = ColumnSort::NONE;
    }

    /**
     * @inheritDoc
     */
    public function setTable(TableInterface $table = null): ColumnInterface
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTable(): ?TableInterface
    {
        return $this->table;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->config->getName();
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->config->getLabel();
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): ColumnConfigInterface
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function isSorted(): bool
    {
        return $this->config->isSortable() && ($this->sortDirection !== ColumnSort::NONE);
    }

    /**
     * @inheritDoc
     */
    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    /**
     * @inheritDoc
     */
    public function setSortDirection(string $sort): ColumnInterface
    {
        if ($this->config->isSortable() && ColumnSort::isValid($sort, true)) {
            $this->sortDirection = $sort;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createHeadView(View\TableView $tableView): View\HeadView
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        $view = $type->createHeadView($this, $tableView);

        $type->buildHeadView($view, $this, $options);

        return $view;
    }

    /**
     * @inheritDoc
     */
    public function createCellView(View\RowView $rowView, RowInterface $row): View\CellView
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        $view = $type->createCellView($this, $rowView);

        $type->buildCellView($view, $this, $row, $options);

        return $view;
    }

    /**
     * @inheritDoc
     */
    public function applySort(AdapterInterface $adapter, ActiveSort $activeSort): void
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        $type->applySort($adapter, $this, $activeSort, $options);
    }
}

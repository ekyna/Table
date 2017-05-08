<?php

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
class Column implements ColumnInterface
{
    /**
     * @var ColumnConfigInterface
     */
    private $config;

    /**
     * @var TableInterface
     */
    private $table;

    /**
     * @var string
     */
    private $sortDirection;


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
    public function setTable(TableInterface $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->config->getName();
    }

    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->config->getLabel();
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function isSorted()
    {
        return $this->config->isSortable() && $this->sortDirection !== ColumnSort::NONE;
    }

    /**
     * @inheritdoc
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    /**
     * @inheritdoc
     */
    public function setSortDirection($direction)
    {
        if ($this->config->isSortable() && ColumnSort::isValid($direction, true)) {
            $this->sortDirection = $direction;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createHeadView(View\TableView $tableView)
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
    public function createCellView(View\RowView $rowView, RowInterface $row)
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
    public function applySort(AdapterInterface $adapter, ActiveSort $activeSort)
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        $type->applySort($adapter, $this, $activeSort, $options);
    }
}

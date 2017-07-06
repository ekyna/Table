<?php

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\View;

/**
 * Class Filter
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Filter implements FilterInterface
{
    /**
     * @var FilterConfigInterface
     */
    private $config;

    /**
     * @var TableInterface
     */
    private $table;


    /**
     * Constructor.
     *
     * @param FilterConfigInterface $config
     */
    public function __construct(FilterConfigInterface $config)
    {
        $this->config = $config;
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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function createAvailableView(View\TableView $tableView)
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        $view = $type->createAvailableView($this, $tableView);

        $type->buildAvailableView($view, $this, $options);

        return $view;
    }

    /**
     * @inheritDoc
     */
    public function createActiveView(View\TableView $tableView, ActiveFilter $activeFilter)
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        $view = $type->createActiveView($this, $tableView);

        $type->buildActiveView($view, $this, $activeFilter, $options);

        return $view;
    }

    /**
     * @inheritDoc
     */
    public function createForm()
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        return $type->createForm($this, $options);
    }

    /**
     * @inheritDoc
     */
    public function applyFilter(AdapterInterface $adapter, ActiveFilter $activeFilter)
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        $type->applyFilter($adapter, $this, $activeFilter, $options);
    }
}

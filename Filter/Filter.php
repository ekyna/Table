<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Context\ActiveFilter;
use Ekyna\Component\Table\Source\AdapterInterface;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\View;
use Symfony\Component\Form\FormInterface;

/**
 * Class Filter
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class Filter implements FilterInterface
{
    private FilterConfigInterface $config;
    private TableInterface $table;


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
    public function setTable(TableInterface $table): FilterInterface
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTable(): TableInterface
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
    public function getConfig(): FilterConfigInterface
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function createAvailableView(View\TableView $tableView): View\AvailableFilterView
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
    public function createActiveView(View\TableView $tableView, ActiveFilter $activeFilter): View\ActiveFilterView
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
    public function createForm(): FormInterface
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        return $type->createForm($this, $options);
    }

    /**
     * @inheritDoc
     */
    public function applyFilter(AdapterInterface $adapter, ActiveFilter $activeFilter): void
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        $type->applyFilter($adapter, $this, $activeFilter, $options);
    }
}

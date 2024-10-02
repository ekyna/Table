<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Action\ActionInterface;
use Ekyna\Component\Table\Filter\FilterInterface;

use function array_keys;
use function in_array;
use function is_null;
use function is_string;
use function strtoupper;
use function uasort;
use function ucfirst;

/**
 * Class Table
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class Table implements TableInterface
{
    private TableConfigInterface $config;

    /** @var Column\ColumnInterface[] */
    private array $columns = [];
    /** @var Filter\FilterInterface[] */
    private array $filters = [];
    /** @var Action\ActionInterface[] */
    private array $actions = [];

    private ?Http\ParametersHelper $parametersHelper = null;
    private ?Context\ContextInterface $context = null;
    private ?Source\AdapterInterface $sourceAdapter = null;

    private bool $locked = false;
    /** @var TableError[] */
    private array $errors = [];

    /**
     * Constructor.
     *
     * @param TableConfigInterface $config
     */
    public function __construct(TableConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function addColumn($column, $type = null, array $options = []): TableInterface
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('You cannot add columns to a locked table.');
        }

        if (!$column instanceof Column\ColumnInterface) {
            if (!is_string($column)) {
                throw new Exception\UnexpectedTypeException($column, ['string', Column\ColumnInterface::class]);
            }

            if (!is_null($type) && !is_string($type) && !$type instanceof Column\ColumnTypeInterface) {
                throw new Exception\UnexpectedTypeException($type, ['string', Column\ColumnTypeInterface::class]);
            }

            if (null === $type) {
                throw new Exception\InvalidArgumentException('Column type guessing is not yet supported.');
                //$column = $this->config->getFactory()->createColumnForProperty($this->config->getDataClass(), $column, null, $options);
            } else {
                $column = $this->config->getFactory()->createColumn($column, $type, $options);
            }
        }

        $this->columns[$column->getName()] = $column;

        $column->setTable($this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getColumn(string $name): Column\ColumnInterface
    {
        if (isset($this->columns[$name])) {
            return $this->columns[$name];
        }

        throw new Exception\OutOfBoundsException(sprintf('Column "%s" does not exist.', $name));
    }

    /**
     * @inheritDoc
     */
    public function hasColumn(string $name): bool
    {
        return isset($this->columns[$name]);
    }

    /**
     * @inheritDoc
     */
    public function removeColumn(string $name): TableInterface
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('You cannot remove columns from a locked table.');
        }

        if (isset($this->columns[$name])) {
            $this->columns[$name]->setTable();

            unset($this->columns[$name]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @inheritDoc
     */
    public function addFilter($filter, $type = null, array $options = []): TableInterface
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('You cannot add filters to a locked table.');
        }

        if (!$filter instanceof Filter\FilterInterface) {
            if (!is_string($filter)) {
                throw new Exception\UnexpectedTypeException($filter, ['string', Filter\FilterInterface::class]);
            }

            if (!is_null($type) && !is_string($type) && !$type instanceof Filter\FilterTypeInterface) {
                throw new Exception\UnexpectedTypeException($type, ['string', Filter\FilterTypeInterface::class]);
            }

            if (null === $type) {
                throw new Exception\InvalidArgumentException('Filter type guessing is not yet supported.');
                //$filter = $this->config->getFactory()->createFilterForProperty($this->config->getDataClass(), $filter, null, $options);
            } else {
                $filter = $this->config->getFactory()->createFilter($filter, $type, $options);
            }
        }

        $this->filters[$filter->getName()] = $filter;

        $filter->setTable($this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilter(string $name): FilterInterface
    {
        if (isset($this->filters[$name])) {
            return $this->filters[$name];
        }

        throw new Exception\OutOfBoundsException(sprintf('Filter "%s" does not exist.', $name));
    }

    /**
     * @inheritDoc
     */
    public function hasFilter(string $name): bool
    {
        return isset($this->filters[$name]);
    }

    /**
     * @inheritDoc
     */
    public function removeFilter(string $name): TableInterface
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('You cannot remove filters from a locked table.');
        }

        if (isset($this->filters[$name])) {
            unset($this->filters[$name]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @inheritDoc
     */
    public function addAction($action, $type = null, array $options = []): TableInterface
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('You cannot add actions to a locked table.');
        }

        if (!$action instanceof Action\ActionInterface) {
            if (!is_string($action)) {
                throw new Exception\UnexpectedTypeException($action, ['string', Action\ActionInterface::class]);
            }

            if (!is_null($type) && !is_string($type) && !$type instanceof Action\ActionTypeInterface) {
                throw new Exception\UnexpectedTypeException($type, ['string', Action\ActionTypeInterface::class]);
            }

            if (null === $type) {
                throw new Exception\InvalidArgumentException('Action type must be defined.');
            }

            $action = $this->config->getFactory()->createAction($action, $type, $options);
        }

        $this->actions[$action->getName()] = $action;

        $action->setTable($this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAction(string $name): ActionInterface
    {
        if (isset($this->actions[$name])) {
            return $this->actions[$name];
        }

        throw new Exception\OutOfBoundsException(sprintf('Action "%s" does not exist.', $name));
    }

    /**
     * @inheritDoc
     */
    public function hasAction(string $name): bool
    {
        return isset($this->actions[$name]);
    }

    /**
     * @inheritDoc
     */
    public function removeAction(string $name): TableInterface
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('You cannot remove actions from a locked table.');
        }

        if (isset($this->actions[$name])) {
            $this->actions[$name]->setTable();

            unset($this->actions[$name]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @inheritDoc
     */
    public function addError(TableError $error): TableInterface
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): TableConfigInterface
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function getHash(): string
    {
        return $this->config->getHash();
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
    public function handleRequest(object $request = null): ?object
    {
        if ($this->locked) {
            throw new Exception\LogicException("Context has already been loaded. Don't call handleRequest() more than once.");
        }

        $this->locked = true;

        if ($request) {
            $handler = new Http\RequestHandler($this);
            if (null !== $response = $handler->handleRequest($request)) {
                return $response;
            }
        }

        return null;
    }

    /**
     * Returns the context.
     *
     * @return Context\ContextInterface
     */
    public function getContext(): Context\ContextInterface
    {
        if (!$this->locked) {
            throw new Exception\LogicException(
                'Context is not yet available. You must first call handleRequest()'
            );
        }

        if (null === $this->context) {
            $visibleColumns = [];
            foreach ($this->columns as $name => $column) {
                if (!$column->isVisible()) {
                    continue;
                }

                $visibleColumns[] = $name;
            }

            $this->context = new Context\Context();
            $this->context
                ->setVisibleColumns($visibleColumns)
                ->setMaxPerPage($this->config->getPerPageChoices()[0]);
        }

        return $this->context;
    }

    /**
     * @inheritDoc
     */
    public function getParametersHelper(): Http\ParametersHelper
    {
        if (!$this->locked) {
            throw new Exception\LogicException(
                'Parameters helper is not yet available. You must first call handleRequest()'
            );
        }

        if (null !== $this->parametersHelper) {
            return $this->parametersHelper;
        }

        return $this->parametersHelper = new Http\ParametersHelper($this->getName(), $this->getSelectionMode());
    }

    /**
     * @inheritDoc
     */
    public function getSourceAdapter(): Source\AdapterInterface
    {
        if (!$this->locked) {
            throw new Exception\LogicException(
                'Source adapter is not yet available. You must first call handleRequest()'
            );
        }

        if (null !== $this->sourceAdapter) {
            return $this->sourceAdapter;
        }

        return $this->sourceAdapter = $this->getConfig()->getFactory()->createAdapter($this);
    }

    /**
     * Sorts the columns and filers.
     */
    public function sortElements(): void
    {
        $sort = function ($a, $b): int {
            /**
             * @var Column\ColumnInterface|Filter\FilterInterface $a
             * @var Column\ColumnInterface|Filter\FilterInterface $b
             */
            $aPos = $a->getConfig()->getPosition();
            $bPos = $b->getConfig()->getPosition();

            if ($aPos === $bPos) {
                return 0;
            }

            return $aPos > $bPos ? 1 : -1;
        };

        uasort($this->columns, $sort);
        uasort($this->filters, $sort);
        // TODO actions
    }

    /**
     * @inheritDoc
     */
    public function createView(): View\TableView
    {
        $context = $this->getContext();

        $type = $this->config->getType();
        $options = $this->config->getOptions();

        // Sort the columns, filters and actions
        $this->sortElements();

        // Create the table view
        $tableView = $type->createView($this);

        // Build the table view
        $type->buildView($tableView, $this, $options);

        // If the data can be filtered
        if ($this->config->isFilterable()) {
            // Builds the available filters views
            foreach ($this->filters as $name => $child) {
                $tableView->available_filters[$name] = $child->createAvailableView($tableView);
            }

            // Add filter form view
            if ($form = $context->getFilterForm()) {
                $tableView->filter_form = $form->createView();
                $tableView->filter_label = $context->getFilterLabel();
            }

            // Builds active filters from the context
            $activeFilters = $context->getActiveFilters();
            if (!empty($activeFilters)) {
                foreach ($activeFilters as $activeFilter) {
                    foreach ($this->filters as $name => $child) {
                        if ($name === $activeFilter->getFilterName()) {
                            $filter = $child->createActiveView($tableView, $activeFilter);
                            $tableView->active_filters[$activeFilter->getId()] = $filter;
                            continue 2;
                        }
                    }
                }
            }
        }

        // If the columns can be sorted and there is an active sort
        if ($this->config->isSortable() && $activeSort = $context->getActiveSort()) {
            // Updates the columns sort directions from the context
            foreach ($this->columns as $name => $child) {
                if ($name === $activeSort->getColumnName()) {
                    $child->setSortDirection($activeSort->getDirection());
                } else {
                    $child->setSortDirection(Util\ColumnSort::NONE);
                }
            }
        }

        // Builds the columns heads views
        $visibleColumns = $context->getVisibleColumns();
        foreach ($this->columns as $name => $child) {
            // Skip non visible columns
            // TODO check if column is toggleable
            if (!in_array($name, $visibleColumns, true)) {
                continue;
            }

            $tableView->heads[$name] = $child->createHeadView($tableView);
        }

        // Ui
        $tableView->ui = $this->buildUiVars();

        // Grid
        $grid = $this->getSourceAdapter()->getGrid($this->getContext());

        $tableView->pager = $grid->getPager();

        // For each data row
        foreach ($grid->getRows() as $row) {
            // Builds the row view
            $rowView = $this->createRowView($tableView);
            $type->buildRowView($rowView, $row, $options);

            $rowView->identifier = $row->getIdentifier();
            $rowView->selected = in_array($row->getIdentifier(), $context->getSelectedIdentifiers(), true);

            // Builds the cells views
            foreach ($this->columns as $name => $child) {
                // Skip non visible columns
                if (!in_array($name, $visibleColumns, true)) {
                    continue;
                }

                $rowView->cells[$name] = $child->createCellView($rowView, $row);
            }

            $tableView->rows[] = $rowView;
        }

        return $tableView;
    }

    /**
     * Builds the table view's ui variables.
     *
     * @return array
     */
    private function buildUiVars(): array
    {
        $context = $this->getContext();
        $params = $this->getParametersHelper();

        $vars = [
            'select'    => false,
            'config'    => false,
            'batch'     => false,
            'export'    => false,
            'profile'   => false,
            'page_name' => $params->getPageName(),
        ];

        // Select
        $select = null !== $mode = $this->getSelectionMode();

        if ($select) {
            $vars['select'] = [
                'multiple' => $mode === Util\Config::SELECTION_MULTIPLE,
                'name'     => $params->getIdentifiersName(),
                'all'      => $params->getAllName(),
            ];
        }

        // Batch
        if ($this->config->isBatchable() && !empty($this->actions)) {
            $actionChoices = [];
            foreach ($this->actions as $action) {
                $label = $action->getConfig()->getLabel();

                $actionChoices[] = [
                    'value' => $action->getName(),
                    'label' => $label ?: ucfirst($action->getName()),
                ];
            }

            $vars['batch'] = [
                'choices' => $actionChoices,
                'name'    => $params->getActionName(),
                'button'  => $params->getBatchButton(),
            ];
        }

        // Export
        if ($this->config->isExportable() && $this->config->hasExportAdapters()) {
            $formats = ['csv', 'json', 'xml'];

            $exportChoices = [];
            foreach ($this->config->getExportAdapters() as $adapter) {
                foreach ($formats as $index => $format) {
                    if (!$adapter->supports($format)) {
                        continue;
                    }

                    $exportChoices[] = [
                        'value' => $format,
                        'label' => strtoupper($format),
                    ];

                    unset($formats[$index]);
                }
            }

            $vars['export'] = [
                'choices' => $exportChoices,
                'name'    => $params->getFormatName(),
                'button'  => $params->getExportButton(),
            ];
        }

        // Config
        if ($this->config->isConfigurable()) {
            // Visible columns choices
            $visibleColumns = $context->getVisibleColumns();
            $columnChoices = [];
            foreach ($this->columns as $column) {
                $columnChoices[] = [
                    'value'  => $column->getName(),
                    'label'  => $column->getLabel(),
                    'active' => in_array($column->getName(), $visibleColumns, true),
                ];
            }

            // Max per page choices
            $maxPerPage = $context->getMaxPerPage();
            $maxPerPageChoices = [];
            foreach ($this->config->getPerPageChoices() as $choice) {
                $maxPerPageChoices[] = [
                    'value'  => $choice,
                    'label'  => $choice,
                    'active' => $choice == $maxPerPage,
                ];
            }

            $vars['config'] = [
                'column'       => [
                    'choices' => $columnChoices,
                    'name'    => $params->getColumnsName(),
                ],
                'max_per_page' => [
                    'choices' => $maxPerPageChoices,
                    'name'    => $params->getMaxPerPageName(),
                ],
                'button'       => $params->getConfigButton(),
            ];
        }

        // Profiles
        if ($this->config->isProfileable()) {
            $profileChoices = [];
            if (null !== $storage = $this->getConfig()->getProfileStorage()) {
                foreach ($storage->all($this) as $profile) {
                    $profileChoices[] = [
                        'value' => $profile->getKey(),
                        'label' => $profile->getName(),
                    ];
                }
            }
            $vars['profile'] = [
                'select' => [
                    'name'          => $params->getProfileChoiceName(),
                    'choices'       => $profileChoices,
                    'load_button'   => $params->getProfileLoadButton(),
                    'edit_button'   => $params->getProfileEditButton(),
                    'remove_button' => $params->getProfileRemoveButton(),
                ],
                'create' => [
                    'name'   => $params->getProfileNameName(),
                    'button' => $params->getProfileCreateButton(),
                ],
            ];
        }

        return $vars;
    }

    /**
     * Returns the table's selection mode.
     *
     * @return string|null
     */
    private function getSelectionMode(): ?string
    {
        if (null !== $mode = $this->config->getSelectionMode()) {
            return $mode;
        }

        if ($this->config->isBatchable() && !empty($this->actions)) {
            return Util\Config::SELECTION_MULTIPLE;
        }

        if ($this->config->isExportable() && $this->config->hasExportAdapters()) {
            return Util\Config::SELECTION_MULTIPLE;
        }

        return null;
    }

    /**
     * Creates the row view.
     *
     * @param View\TableView $view
     *
     * @return View\RowView
     */
    private function createRowView(View\TableView $view): View\RowView
    {
        return new View\RowView($view);
    }
}

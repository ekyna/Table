<?php

declare(strict_types=1);

namespace Ekyna\Component\Table;

use Symfony\Component\HttpFoundation\Request;

use function is_string;
use function sprintf;

/**
 * Class TableBuilder
 * @package Ekyna\Component\Table
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TableBuilder extends TableConfigBuilder implements TableBuilderInterface
{
    /** @var Column\ColumnBuilderInterface[] */
    private array $columns           = [];
    private array $unresolvedColumns = [];
    /** @var Filter\FilterBuilderInterface[] */
    private array $filters           = [];
    private array $unresolvedFilters = [];
    /** @var Action\ActionBuilderInterface[] */
    private array $actions           = [];
    private array $unresolvedActions = [];


    /**
     * Constructor.
     *
     * @param string                $name
     * @param TableFactoryInterface $factory
     * @param array                 $options
     */
    public function __construct(string $name, TableFactoryInterface $factory, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setFactory($factory);
    }

    /**
     * @inheritDoc
     */
    public function addColumn($column, string $type = null, array $options = []): TableBuilderInterface
    {
        $this->preventIfLocked();

        if ($column instanceof Column\ColumnBuilderInterface) {
            $this->columns[$column->getName()] = $column;

            // In case an unresolved column with the same name exists
            unset($this->unresolvedColumns[$column->getName()]);

            return $this;
        }

        if (!is_string($column)) {
            throw new Exception\UnexpectedTypeException($column, ['string', Column\ColumnBuilderInterface::class]);
        }

        $this->columns[$column] = null;
        $this->unresolvedColumns[$column] = [
            'type'    => $type,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Creates a column builder.
     *
     * @param string      $name
     * @param string|null $type
     * @param array       $options
     *
     * @return Column\ColumnBuilderInterface
     */
    public function createColumn(string $name, string $type = null, array $options = []): Column\ColumnBuilderInterface
    {
        $this->preventIfLocked();

        if (null !== $type) {
            return $this->getFactory()->createColumnBuilder($name, $type, $options);
        }

        throw new Exception\InvalidArgumentException('Column type guessing is not yet supported.');
        // TODO return $this->getFormFactory()->createBuilderForProperty($this->getDataClass(), $name, $options);
    }

    /**
     * @inheritDoc
     */
    public function getColumn(string $name): Column\ColumnBuilderInterface
    {
        $this->preventIfLocked();

        if (isset($this->unresolvedColumns[$name])) {
            return $this->resolveColumn($name);
        }

        if (isset($this->columns[$name])) {
            return $this->columns[$name];
        }

        throw new Exception\InvalidArgumentException(sprintf('The column with the name "%s" does not exist.', $name));
    }

    /**
     * @inheritDoc
     */
    public function removeColumn(string $name): TableBuilderInterface
    {
        $this->preventIfLocked();

        unset($this->unresolvedColumns[$name], $this->columns[$name]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addFilter($filter, string $type = null, array $options = []): TableBuilderInterface
    {
        $this->preventIfLocked();

        if ($filter instanceof Filter\FilterBuilderInterface) {
            $this->filters[$filter->getName()] = $filter;

            // In case an unresolved filter with the same name exists
            unset($this->unresolvedFilters[$filter->getName()]);

            return $this;
        }

        if (!is_string($filter)) {
            throw new Exception\UnexpectedTypeException($filter, ['string', Filter\FilterBuilderInterface::class]);
        }

        $this->filters[$filter] = null;
        $this->unresolvedFilters[$filter] = [
            'type'    => $type,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Creates a filter builder.
     *
     * @param string      $name
     * @param string|null $type
     * @param array       $options
     *
     * @return Filter\FilterBuilderInterface
     */
    public function createFilter(string $name, string $type = null, array $options = []): Filter\FilterBuilderInterface
    {
        $this->preventIfLocked();

        if (null !== $type) {
            return $this->getFactory()->createFilterBuilder($name, $type, $options);
        }

        throw new Exception\InvalidArgumentException('Filter type guessing is not yet supported.');
        // TODO return $this->getFormFactory()->createFilterBuilderForProperty($this->getDataClass(), $name, $options);
    }

    /**
     * @inheritDoc
     */
    public function getFilter(string $name): Filter\FilterBuilderInterface
    {
        $this->preventIfLocked();

        if (isset($this->unresolvedFilters[$name])) {
            return $this->resolveFilter($name);
        }

        if (isset($this->filters[$name])) {
            return $this->filters[$name];
        }

        throw new Exception\InvalidArgumentException(sprintf('The filter with the name "%s" does not exist.', $name));
    }

    /**
     * @inheritDoc
     */
    public function removeFilter(string $name): TableBuilderInterface
    {
        $this->preventIfLocked();

        unset($this->unresolvedFilters[$name], $this->filters[$name]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addAction($action, string $type = null, array $options = []): TableBuilderInterface
    {
        $this->preventIfLocked();

        if ($action instanceof Action\ActionBuilderInterface) {
            $this->actions[$action->getName()] = $action;

            // In case an unresolved action with the same name exists
            unset($this->unresolvedActions[$action->getName()]);

            return $this;
        }

        if (!is_string($action)) {
            throw new Exception\UnexpectedTypeException($action, 'string or ' . Action\ActionBuilderInterface::class);
        }

        $this->actions[$action] = null;
        $this->unresolvedActions[$action] = [
            'type'    => $type,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Creates a action builder.
     *
     * @param string      $name
     * @param string|null $type
     * @param array       $options
     *
     * @return Action\ActionBuilderInterface
     */
    public function createAction(string $name, string $type = null, array $options = []): Action\ActionBuilderInterface
    {
        $this->preventIfLocked();

        if (null !== $type) {
            return $this->getFactory()->createActionBuilder($name, $type, $options);
        }

        throw new Exception\InvalidArgumentException('Action type guessing is not yet supported.');
        // TODO return $this->getFormFactory()->createActionBuilderForProperty($this->getDataClass(), $name, $options);
    }

    /**
     * @inheritDoc
     */
    public function getAction(string $name): Action\ActionBuilderInterface
    {
        $this->preventIfLocked();

        if (isset($this->unresolvedActions[$name])) {
            return $this->resolveAction($name);
        }

        if (isset($this->actions[$name])) {
            return $this->actions[$name];
        }

        throw new Exception\InvalidArgumentException(sprintf('The action with the name "%s" does not exist.', $name));
    }

    /**
     * @inheritDoc
     */
    public function removeAction(string $name): TableBuilderInterface
    {
        $this->preventIfLocked();

        unset($this->unresolvedActions[$name], $this->actions[$name]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTableConfig(): TableConfigInterface
    {
        if (!$this->getSource()) {
            throw new Exception\BadMethodCallException("You must define the table's source first.");
        }

        /** @var self $config */
        $config = parent::getTableConfig();

        $config->columns = [];
        $config->filters = [];
        $config->actions = [];

        return $config;
    }

    /**
     * @inheritDoc
     */
    public function getTable(Request $request = null): TableInterface
    {
        $this->preventIfLocked();

        $this->resolveElements();

        $table = new Table($this->getTableConfig());

        foreach ($this->columns as $column) {
            $table->addColumn($column->getColumn());
        }

        foreach ($this->filters as $filter) {
            $table->addFilter($filter->getFilter());
        }

        foreach ($this->actions as $action) {
            $table->addAction($action->getAction());
        }

        $table->sortElements();

        return $table;
    }

    /**
     * Converts an unresolved column into a column builder instance.
     *
     * @param string $name The name of the unresolved column
     *
     * @return Column\ColumnBuilderInterface
     */
    private function resolveColumn(string $name): Column\ColumnBuilderInterface
    {
        $info = $this->unresolvedColumns[$name];
        $child = $this->createColumn($name, $info['type'], $info['options']);
        $this->columns[$name] = $child;
        unset($this->unresolvedColumns[$name]);

        return $child;
    }

    /**
     * Converts an unresolved filter into a filter builder instance.
     *
     * @param string $name The name of the unresolved filter
     *
     * @return Filter\FilterBuilderInterface
     */
    private function resolveFilter(string $name): Filter\FilterBuilderInterface
    {
        $info = $this->unresolvedFilters[$name];
        $child = $this->createFilter($name, $info['type'], $info['options']);
        $this->filters[$name] = $child;
        unset($this->unresolvedFilters[$name]);

        return $child;
    }

    /**
     * Converts an unresolved action into a action builder instance.
     *
     * @param string $name The name of the unresolved action
     *
     * @return Action\ActionBuilderInterface
     */
    private function resolveAction(string $name): Action\ActionBuilderInterface
    {
        $info = $this->unresolvedActions[$name];
        $child = $this->createAction($name, $info['type'], $info['options']);
        $this->actions[$name] = $child;
        unset($this->unresolvedActions[$name]);

        return $child;
    }

    /**
     * Converts all unresolved elements into builder instances.
     */
    private function resolveElements(): void
    {
        foreach ($this->unresolvedColumns as $name => $info) {
            $this->columns[$name] = $this->createColumn($name, $info['type'], $info['options']);
        }
        $this->unresolvedColumns = [];

        foreach ($this->unresolvedFilters as $name => $info) {
            $this->filters[$name] = $this->createFilter($name, $info['type'], $info['options']);
        }
        $this->unresolvedFilters = [];

        foreach ($this->unresolvedActions as $name => $info) {
            $this->actions[$name] = $this->createAction($name, $info['type'], $info['options']);
        }
        $this->unresolvedActions = [];
    }
}

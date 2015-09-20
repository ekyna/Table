<?php

namespace Ekyna\Component\Table;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;
use Ekyna\Component\Table\Exception\RuntimeException;
use Ekyna\Component\Table\Request\RequestHelper;
use Ekyna\Component\Table\Util\ColumnSort;
use Ekyna\Component\Table\View\AvailableFilter;
use Ekyna\Component\Table\View\Cell;
use Ekyna\Component\Table\View\Column;
use Ekyna\Component\Table\View\Row;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\Exception\ExceptionInterface as PropertyAccessException;

/**
 * Class Table
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class Table
{
    /**
     * @var TableConfig
     */
    private $config;

    /**
     * @var TableFactory;
     */
    private $factory;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $data;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var \Ekyna\Component\Table\Request\RequestHelper
     */
    private $requestHelper;

    /**
     * Constructor.
     */
    public function __construct(TableConfig $config)
    {
        $this->config = $config;

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->requestHelper = new RequestHelper();
    }

    /**
     * Returns the table name (TableConfig::getName() alias).
     *
     * @return string
     */
    public function getName()
    {
        return $this->config->getName();
    }

    /**
     * Returns the config.
     *
     * @return \Ekyna\Component\Table\TableConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the factory.
     *
     * @param \Ekyna\Component\Table\TableFactory $factory
     * @return Table
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
        return $this;
    }

    /**
     * Returns the factory.
     *
     * @return \Ekyna\Component\Table\TableFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Sets the data.
     *
     * @param mixed $data
     * @return Table
     */
    public function setData($data)
    {
        if (null !== $data && !$data instanceof Collection) {
            $this->data = new ArrayCollection((array) $data);
        } else {
            $this->data = $data;
        }
        return $this;
    }

    /**
     * Returns the data.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the entityManager.
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     *
     * @return Table
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * Returns the entityManager.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Sets the request.
     *
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->requestHelper->setRequest($request);
    }

    /**
     * Sets the propertyAccessor.
     *
     * @return \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    public function getPropertyAccessor()
    {
        return $this->propertyAccessor;
    }

    /**
     * Returns the current row data for the given key or property name.
     *
     * @param $keyOrProperty
     *
     * @return mixed
     */
    public function getCurrentRowData($keyOrProperty = null)
    {
        if(null === $keyOrProperty) {
            return $this->data->current();
        }
        try {
            return $this->propertyAccessor->getValue($this->data->current(), $keyOrProperty);
        } catch(PropertyAccessException $e) {
        }
        return null;
    }

    /**
     * Returns the current row key.
     *
     * @return mixed
     */
    public function getCurrentRowKey()
    {
        return key($this->data->current());
    }

    /**
     * Creates and returns the table view.
     *
     * @param Request $request
     * @throws RuntimeException
     * @return TableView
     */
    public function createView(Request $request = null)
    {
        if (null !== $request) {
            $this->requestHelper->setRequest($request);
        }

        $view = new TableView();
        $view->name = $this->getName();
        $view->options = array(
            'selector' => $this->config->getSelector(),
        );
        $view->selection_form = $this->config->getSelector();

        if (!$this->config->hasColumns()) {
            throw new RuntimeException('Table has no columns and cannot be generated.');
        }

        $class = $this->config->getDataClass();
        $alias = strtolower(substr($class, strrpos($class, '\\')+1, 1));

        // TODO What if data is already set ?
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select($alias)
            ->from($this->config->getDataClass(), $alias);

        if (null !== $customizeQb = $this->config->getCustomizeQb()) {
            $customizeQb($queryBuilder, $alias);
        }

        $this->generateFilters($queryBuilder, $view);
        $this->generateColumns($queryBuilder, $view);

        $currentPage = $this->requestHelper->getVar($this->getName().'_page', 1);

        /*$dql = $queryBuilder->getQuery()->getDQL();
        var_dump($dql);
        exit();*/

        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($adapter);
        $pager
            ->setNormalizeOutOfRangePages(true)
            ->setMaxPerPage($this->config->getMaxPerPage())
            ->setCurrentPage($currentPage)
        ;

        $this->setData($pager->getCurrentPageResults());

        if($currentPage != $pager->getCurrentPage()) {
            $this->requestHelper->setVar($this->getName().'_page', $pager->getCurrentPage());
        }

        $view->pager = $pager;

        $this->generateCells($view);

        return $view;
    }

    /**
     * Creates an active filter.
     *
     * @param array $filterOptions
     * @param array $formData
     */
    private function createActiveFilter($filterOptions, $formData)
    {
        // TODO real form validation
        $value = $formData['value'];
        if (null === $value || (is_array($value) && empty($value))) {
            return;
        }

        $activesFilters = $this->requestHelper->getSessionVar($this->getName().'_filters', array());
        $activesFilters[] = array(
            'full_name'     => $filterOptions['full_name'],
            'id'            => uniqid(),
            'type'          => $filterOptions['type'],
            'property_path' => $filterOptions['property_path'],
            'label'         => $filterOptions['label'],
            'operator'      => $formData['operator'],
            'value'         => $value,
        );
        $this->requestHelper->setVar($this->getName().'_filters', $activesFilters);
    }

    /**
     * Removes an active filter.
     *
     * @param string $filterId
     */
    private function removeActiveFilter($filterId)
    {
        $tmp = array();
        $activesFilters = $this->requestHelper->getSessionVar($this->getName().'_filters', array());
        foreach ($activesFilters as $activeFilter) {
            if ($activeFilter['id'] != $filterId) {
                $tmp[] = $activeFilter;
            }
        }
        $this->requestHelper->setVar($this->getName().'_filters', $tmp);
    }

    /**
     * Generates the filters.
     *
     * @param QueryBuilder $queryBuilder
     * @param TableView    $view
     */
    private function generateFilters(QueryBuilder $queryBuilder, TableView $view)
    {
        if (null !== $removedFilterId = $this->requestHelper->getRequestVar('remove_filter')) {
            // Remove an active filter
            $this->removeActiveFilter($removedFilterId);

        } elseif (null !== $requestedFilterFullName = $this->requestHelper->getRequestVar('add_filter')) {
            // Filter widget
            if (null !== $filterOptions = $this->config->findFilterByFullName($requestedFilterFullName)) {
                $type = $this->factory->getFilterType($filterOptions['type']);

                // Build Form
                $formBuilder = $this->factory->getFormFactory()->createBuilder();
                $type->buildFilterFrom($formBuilder, $filterOptions);
                $form = $formBuilder
                    ->add('add_filter', 'hidden', array(
                        'data' => $filterOptions['full_name']
                    ))
                    ->getForm();

                // Handle request
                $form->handleRequest($this->requestHelper->getRequest());
                if ($form->isValid()) {
                    $this->createActiveFilter($filterOptions, $form->getData());
                } else {
                    $view->filter_label = $filterOptions['label'];
                    $view->filter_form = $formBuilder->getForm()->createView();
                }
            }
        }

        // Build available filters
        foreach ($this->config->getFilters() as $filterOptions) {
            // Filter type
            $type = $this->factory->getFilterType($filterOptions['type']);

            // Build available filter
            $availableFilter = new AvailableFilter();
            $type->buildAvailableFilter($availableFilter, $filterOptions);
            $view->available_filters[] = $availableFilter;
        }

        // Build actives Filters
        foreach ($this->requestHelper->getSessionVar($this->getName().'_filters', array()) as $data) {
            // Filter type
            $type = $this->factory->getFilterType($data['type']);

            if (null === $options = $this->config->findFilterByFullName($data['full_name'])) {
                throw new RuntimeException(sprintf('Filter "%s" not found.', $data['full_name']));
            }

            // Build actives Filter
            $type->buildActiveFilter($view, $data, $options);
            // Configure query builder
            $type->applyFilter($queryBuilder, $data, $options);
        }
    }

    /**
     * Generates the columns.
     *
     * @param QueryBuilder $queryBuilder
     * @param TableView $view
     */
    private function generateColumns(QueryBuilder $queryBuilder, TableView $view)
    {
        $sortDirs = array(ColumnSort::ASC, ColumnSort::DESC);
        $newSortedColumnName = null;
        $columns = $this->config->getColumns();

        // Check if user requested a new column sorting.
        foreach ($columns as $columnOptions) {
            if (array_key_exists('sortable', $columnOptions) && true === $columnOptions['sortable']) {
                $key = $columnOptions['full_name'].'_sort';
                $sortDir = $this->requestHelper->getRequestVar($key, ColumnSort::NONE);
                if (in_array($sortDir, $sortDirs)) {
                    $newSortedColumnName = $columnOptions['name'];
                    break;
                }
            }
        }

        // If new sorted column purge session sort.
        if (null !== $newSortedColumnName) {
            foreach ($columns as $columnOptions) {
                if ($newSortedColumnName !== $columnOptions['name']) {
                    $this->requestHelper->unsetVar($columnOptions['full_name'].'_sort');
                }
            }
        }

        $alias = $queryBuilder->getRootAliases()[0];

        // Configure columns options.
        $userSort = false;
        foreach ($columns as $columnOptions) {

            $columnOptions['sort_dir'] = ColumnSort::NONE;
            $columnOptions['sorted']   = false;

            if (array_key_exists('sortable', $columnOptions) && true === $columnOptions['sortable']) {
                $key = $columnOptions['full_name'].'_sort';
                $sortDir = $this->requestHelper->getRequestVar($key, ColumnSort::NONE);

                if (in_array($sortDir, $sortDirs)) {
                    $columnOptions['sort_dir'] = $sortDir;
    	            $columnOptions['sorted']   = true;

                    // Configure query builder.
                    $queryBuilder->addOrderBy($alias.'.'.$columnOptions['property_path'], strtoupper($sortDir));
                    $userSort = true;

                    // Stores user sorting in session.
                    $this->requestHelper->setVar($key, $sortDir);
                }
            }

            $column = new Column();

            $type = $this->factory->getColumnType($columnOptions['type']);
            $type->buildViewColumn($column, $this, $columnOptions);

            $view->columns[] = $column;
        }
        unset($columnOptions);

        // Default sort if not user sorted.
        $defaultSorts = $this->config->getDefaultSorts();
        if (!$userSort && !empty($defaultSorts)) {
            foreach($defaultSorts as $defaultSort) {
                list($sortBy, $sortDir) = explode(' ', $defaultSort);
                $queryBuilder->addOrderBy($alias . '.' . $sortBy, $sortDir);
            }
        }
    }

    /**
     * Generates the cells.
     *
     * @param TableView $view
     */
    private function generateCells(TableView $view)
    {
        $sortDirs = array(ColumnSort::ASC, ColumnSort::DESC);
        $columns = $this->config->getColumns();

        foreach ($columns as &$columnOptions) {
            $key = $columnOptions['full_name'].'_sort';
            $sortDir = $this->requestHelper->getVar($key, ColumnSort::NONE);
            $columnOptions['sorted'] = in_array($sortDir, $sortDirs);
        }
        unset($columnOptions);

        $this->data->first();
        while ($this->data->current()) {

            $row = new Row($this->getCurrentRowData('id')); // TODO getCurrentRowKey() ?

            foreach ($columns as $columnOptions) {
                $cell = new Cell();

                $type = $this->factory->getColumnType($columnOptions['type']);
                $type->buildViewCell($cell, $this, $columnOptions);

                $row->cells[] = $cell;
            }
            $view->rows[] = $row;

            $this->data->next();
        }
    }
}

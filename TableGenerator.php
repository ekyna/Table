<?php

namespace Ekyna\Component\Table;

use Doctrine\ORM\EntityManager;
use Ekyna\Component\Table\Exception\RuntimeException;
use Ekyna\Component\Table\Util\ColumnSort;
use Ekyna\Component\Table\Util\FilterOperator;
use Ekyna\Component\Table\View\AvailableFilter;
use Ekyna\Component\Table\View\Column;
use Ekyna\Component\Table\View\Cell;
use Ekyna\Component\Table\View\Row;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * TableGenerator
 */
class TableGenerator
{
    /**
     * @var \Ekyna\Component\Table\TableFactory
     */
    private $tableFactory;

    /**
     * @var 
     */
    private $formFactory;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @param TableFactory                                   $tableFactory
     * @param \Symfony\Component\Form\FormFactory            $formFactory
     * @param \Doctrine\ORM\EntityManager                    $em
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(
        TableFactory $tableFactory,
        FormFactory $formFactory,
        EntityManager $em,
        RequestStack $requestStack,
        $attributeBagName = 'ekyna_table_attributes'
    ) {
        $this->tableFactory = $tableFactory;
        $this->formFactory = $formFactory;
        $this->entityManager = $em;
        $this->requestStack = $requestStack;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session
     */
    private function getSession()
    {
        return $this->requestStack->getCurrentRequest()->getSession();
    }

    /**
     * Returns a user var according to request (GET & POST)
     * 
     * @param string $key
     * @param string $default
     * 
     * @return mixed
     */
    public function getUserRequest($key, $default = null)
    {
        $value = $default;
        if(null !== $query = $this->getRequest()->query->get($key)) {
            $value = $query;
        }elseif(null !== $request = $this->getRequest()->request->get($key)) {
            $value = $request;
        }
        return $value;
    }

    /**
     * Returns a user var according to session and request (GET & POST)
     * and store the value if not equal to default
     * 
     * @param string $key
     * @param string $default
     * 
     * @return mixed
     */
    public function getUserVar($key, $default = null)
    {
        $value = $this->getSession()->get($key, $default);
        if(null !== $request = $this->getUserRequest($key)) {
            $value = $request;
        }
        if($value !== $default) {
            $this->getSession()->set($key, $value);
        }
        return $value;
    }

    /**
     * Generates a TableView base on given Table
     * 
     * @param \Ekyna\Component\Table\Table $table
     * 
     * @return \Ekyna\Component\Table\TableView
     */
    public function generateView(Table $table)
    {
        $view = new TableView();
        $view->name = $table->getName();

        if (!$table->hasColumns()) {
            throw new RuntimeException('Table has no columns and cannot be generated.');
        }

        // Generates columns
        list($sortBy, $sortDir, $sortedColumnName) = $this->generateColumns($table, $view);

        // Generates filters
        list($expressions, $parameters) = $this->generateFilters($table, $view);

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('a')
            ->from($table->getEntityClass(), 'a');

        if (null !== $sortBy && null !== $sortDir) {
            $queryBuilder->addOrderBy('a.'.$sortBy, $sortDir);
        } elseif (null !== $sort = $table->getDefaultSort()) {
            list($sortBy, $sortDir) = $sort;
            $queryBuilder->addOrderBy('a.'.$sortBy, $sortDir);
        }
        if (count($expressions) > 0) {
            $expressionCount = 0;
            foreach ($expressions as $expression) {
                $queryBuilder->andWhere($expression);
            }
            $queryBuilder->setParameters($parameters);
        }

        if (null !== $customizeQb = $table->getCustomizeQueryBuilder()) {
            $customizeQb($queryBuilder);
        }
        
        $current_page = $this->getUserVar($table->getName().'_page', 1);

        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($adapter);
        $pager
            ->setNormalizeOutOfRangePages(true)
            ->setMaxPerPage($table->getMaxPerPage())
            ->setCurrentPage($current_page)
        ;

        $entities = $pager->getCurrentPageResults();

        if($current_page != $pager->getCurrentPage()) {
            $this->getSession()->set($table->getName().'_page', $pager->getCurrentPage());
        }

        $view->pager = $pager;

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($entities as $entity) {
            $row = new Row($entity->getId());
            foreach ($table->getColumns() as $columnOptions) {
                $cell = new Cell();
                $type = $this->tableFactory->getColumnType($columnOptions['type']);
                $type->buildViewCell($cell, $accessor, $entity, $columnOptions);
                if($sortedColumnName === $columnOptions['name']) {
                    $cell->vars['sorted'] = true;
                }
                $row->cells[] = $cell;
            }
            $view->rows[] = $row;
        }

        return $view;
    }

    private function createActiveFilter(Table $table, $filterOptions, $formData)
    {
        $activesFilters = $this->getSession()->get($table->getName().'_filters', array());
        $activesFilters[] = array(
            'full_name'     => $filterOptions['full_name'],
            'id'            => uniqid(),
            'type'          => $filterOptions['type'],
            'property_path' => $filterOptions['property_path'],
            'label'         => $filterOptions['label'],
            'operator'      => $formData['operator'],
            'value'         => $formData['value'],
        );
        $this->getSession()->set($table->getName().'_filters', $activesFilters);
    }

    private function removeActiveFilter(Table $table, $filterId)
    {
        $tmp = array();
        $activesFilters = $this->getSession()->get($table->getName().'_filters', array());
        foreach ($activesFilters as $activeFilter) {
            if ($activeFilter['id'] != $filterId) {
                $tmp[] = $activeFilter;
            }
        }
        $this->getSession()->set($table->getName().'_filters', $tmp);
    }
    
    private function generateFilters(Table $table, TableView $view)
    {
        $expressions = $parameters = array();

        if (null !== $removedFilterId = $this->getUserRequest('remove_filter')) {
            // Remove an active filter
            $this->removeActiveFilter($table, $removedFilterId);

        } elseif (null !== $requestedFilterFullName = $this->getUserRequest('add_filter')) {
            // Filter widget
            if (null !== $filterOptions = $table->findFilterByFullName($requestedFilterFullName)) {
                $type = $this->tableFactory->getFilterType($filterOptions['type']);

                // Build Form
                $formBuilder = $this->formFactory->createBuilder();
                $type->buildFilterFrom($formBuilder, $filterOptions);
                $form = $formBuilder
                    ->add('add_filter', 'hidden', array(
                        'data' => $filterOptions['full_name']
                    ))
                    ->getForm();

                // Handle request
                $form->handleRequest($this->getRequest());
                if ($form->isValid()) {
                    $this->createActiveFilter($table, $filterOptions, $form->getData());
                } else {
                    $view->filter_label = $filterOptions['label'];
                    $view->filter_form = $formBuilder->getForm()->createView();
                }
            }
        }

        foreach ($table->getFilters() as $filterOptions) {
            // Filter type
            $type = $this->tableFactory->getFilterType($filterOptions['type']);

            // Build available filters
            $availableFilter = new AvailableFilter();
            $type->buildAvailableFilter($availableFilter, $filterOptions);
            $view->available_filters[] = $availableFilter;
        }

        // Build actives Filters
        $count = 1;
        foreach ($this->getSession()->get($table->getName().'_filters', array()) as $datas) {
            
            $type = $this->tableFactory->getFilterType($datas['type']);
            $type->buildActiveFilters($view, $datas);

            $expressions[] = sprintf('%s.%s %s ?%s', 'a', $datas['property_path'], FilterOperator::getExpression($datas['operator']), $count);
            $parameters[$count] = FilterOperator::formatValue($datas['operator'], $datas['value']);
            
            $count++;
        }

        return array($expressions, $parameters);
    }

    private function generateColumns(Table $table, TableView $view)
    {
        $sortBy = $sortDir = $sortedColumnName = $newSortedColumnName = null;
        $sortDirs = array(ColumnSort::ASC, ColumnSort::DESC);

        // Check if user requested a new column sorting
        foreach ($table->getColumns() as $columnOptions) {
            if (array_key_exists('sortable', $columnOptions) && true === $columnOptions['sortable']) {
                $key = $columnOptions['full_name'].'_sort';
                if (in_array($this->getRequest()->query->get($key, false), $sortDirs) ||
                    in_array($this->getRequest()->request->get($key, false), $sortDirs)) {
                    $newSortedColumnName = $columnOptions['name'];
                    break;
                }
            }
        }

        // If new sorted column purge session sort
        if (null !== $newSortedColumnName) {
            foreach ($table->getColumns() as $columnOptions) {
                if ($newSortedColumnName !== $columnOptions['name']) {
                    $this->getSession()->remove($columnOptions['full_name'].'_sort');
                }
            }
        }
        // Build Columns
        foreach ($table->getColumns() as $columnOptions) {
            $column = new Column();

            $type = $this->tableFactory->getColumnType($columnOptions['type']);
            $type->buildViewColumn($column, $this, $columnOptions);

            // Sortable columns
            if (true === $column->getVar('sortable', false)) {
                $sort = $column->getVar('sort_dir', ColumnSort::NONE);
                if (in_array($sort, $sortDirs)) {
                    $sortedColumnName = $columnOptions['name'];
                    $sortBy = $column->getVar('sort_by');
                    $sortDir = strtoupper($sort);
                }
            }

            $view->columns[] = $column;
        }

        return array($sortBy, $sortDir, $sortedColumnName);
    }
}
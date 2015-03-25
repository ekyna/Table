<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TableBuilder
 * @package Ekyna\Component\Table
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TableBuilder implements TableBuilderInterface
{
    /**
     * @var \Ekyna\Component\Table\TableFactory
     */
    private $factory;

    /**
     * @var TableTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $options;

    /**
     * Constructor.
     * @param TableTypeInterface $type
     * @param array              $options
     */
    public function __construct(TableTypeInterface $type, array $options)
    {
        $this->type = $type;

        $this->columns = array();
        $this->filters = array();

        $this->options = $options;
    }

    /**
     * Sets the factory.
     *
     * @param TableFactory $factory
     * @return TableBuilder
     */
    public function setFactory(TableFactory $factory)
    {
        $this->factory = $factory;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addColumn($name, $type = null, array $options = array())
    {
        if(array_key_exists($name, $this->columns)) {
            throw new InvalidArgumentException(sprintf('Column "%s" is already defined.', $name));
        }
        $this->columns[$name] = array($type, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter($name, $type = null, array $options = array())
    {
        if(array_key_exists($name, $this->filters)) {
            throw new InvalidArgumentException(sprintf('Filter "%s" is already defined.', $name));
        }
        $this->filters[$name] = array($type, $options);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTable(Request $request = null)
    {
        $tableConfig = new TableConfig($this->options['name']);

        $defaultSorts = [];
        $defaultSortsConfig = $this->options['default_sorts'];
        if (is_string($defaultSortsConfig)) {
            $defaultSortsConfig = array($defaultSortsConfig);
        }
        //var_dump($defaultSortsConfig);
        foreach($defaultSortsConfig as $defaultSort) {
            if (!preg_match('#^[a-z_]+ asc|desc$#i', $defaultSort)) {
                throw new \InvalidArgumentException('The "default_sorts" option must be an array of strings formatted as "column_name asc|desc".');
            }
            $defaultSorts[] = $defaultSort;
        }

        if (null !== $this->options['data_class'] && !class_exists($this->options['data_class'])) {
            throw new \InvalidArgumentException(sprintf('The class "%s" does not exist (table data_class option).', $this->options['data_class']));
        }

        $tableConfig
            ->setDataClass($this->options['data_class'])
            ->setDefaultSorts($defaultSorts)
            ->setMaxPerPage($this->options['max_per_page'])
            ->setCustomizeQb($this->options['customize_qb'])
            ->setSelector($this->options['selector'])
            ->setSelectorConfig($this->options['selector_config'])
        ;

        // TODO Batch actions

        if ($tableConfig->getSelector()) {
            $selectorConfig = $tableConfig->getSelectorConfig();
            if (true === $this->options['multiple']) {
                $selectorConfig['multiple'] = true;
            }
            $this->factory->createColumn($tableConfig, 'selector', 'selector', $selectorConfig);
        }

        foreach($this->columns as $name => $definition) {
            list($type, $options) = $definition;
            $this->factory->createColumn($tableConfig, $name, $type, $options);
        }

        foreach($this->filters as $name => $definition) {
            list($type, $options) = $definition;
            $this->factory->createFilter($tableConfig, $name, $type, $options);
        }

        $table = new Table($tableConfig);

        $em = null !== $this->options['em'] ? $this->options['em'] : $this->factory->getEntityManager();

        $table
            ->setFactory($this->factory)
            ->setEntityManager($em)
            ->setData($this->options['data'])
            ->setRequest($request)
        ;

        return $table;
    }
}

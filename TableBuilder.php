<?php

namespace Ekyna\Component\Table;

use Ekyna\Component\Table\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
     */
    public function __construct(TableTypeInterface $type)
    {
        $this->type = $type;

        $this->columns = array();
        $this->filters = array();
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
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->type->setDefaultOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getTable(Request $request = null)
    {
        $tableConfig = new TableConfig($this->options['name']);

        $defaultSort = null;
        if (is_string($this->options['default_sort'])) {
            if (!preg_match('#[a-z_]+ asc|desc#', $this->options['default_sort'])) {
                throw new \InvalidArgumentException('The "default_sort" option must be formatted as "column_name asc|desc".');
            }
            $defaultSort = explode(' ', $this->options['default_sort']);
        } elseif (is_array($this->options['default_sort'])) {
            if (!(is_string($this->options['default_sort'][0]) && in_array($this->options['default_sort'][1], array('asc', 'desc')))) {
                throw new \InvalidArgumentException('The "default_sort" option must be formatted as ["column_name", "asc"|"desc"].');
            }
            $defaultSort = $this->options['default_sort'];
        }

        if (null !== $this->options['data_class'] && !class_exists($this->options['data_class'])) {
            throw new \InvalidArgumentException(sprintf('The class "%s" dose not exists (table data_class option).', $this->options['data_class']));
        }

        $tableConfig
            ->setDataClass($this->options['data_class'])
            ->setDefaultSort($defaultSort)
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

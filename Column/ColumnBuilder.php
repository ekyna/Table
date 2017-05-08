<?php

namespace Ekyna\Component\Table\Column;

use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\Util\Config;

/**
 * Class ColumnBuilder
 * @package Ekyna\Component\Table\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ColumnBuilder implements ColumnBuilderInterface
{
    /**
     * @var bool
     */
    private $locked = false;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ResolvedColumnTypeInterface
     */
    private $type;

    /**
     * @var string
     */
    private $label;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $propertyPath;

    /**
     * @var bool
     */
    private $sortable;

    /**
     * @var array
     */
    private $options;


    /**
     * Constructor.
     *
     * @param string $name
     * @param array  $options
     */
    public function __construct($name, array $options = [])
    {
        Config::validateName($name);

        $this->name = (string)$name;
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    /**
     * @inheritDoc
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * @inheritDoc
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @inheritDoc
     */
    public function getOption($name, $default = null)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $default;
    }

    /**
     * @inheritDoc
     */
    public function setType(ResolvedColumnTypeInterface $type)
    {
        $this->preventIfLocked();

        $this->type = $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLabel($label)
    {
        $this->preventIfLocked();

        $this->label = $label;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPosition($position)
    {
        $this->preventIfLocked();

        $this->position = $position;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPropertyPath($path)
    {
        $this->propertyPath = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSortable($sortable)
    {
        $this->sortable = (bool)$sortable;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getColumnConfig()
    {
        $this->preventIfLocked();

        // This method should be idempotent, so clone the builder
        $config = clone $this;
        $config->locked = true;

        return $config;
    }

    /**
     * @inheritDoc
     */
    public function getColumn()
    {
        $this->preventIfLocked();

        return new Column($this->getColumnConfig());
    }

    /**
     * Prevents method call if the config builder is locked.
     *
     * @throws Exception\BadMethodCallException
     */
    private function preventIfLocked()
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('The config builder cannot be modified anymore.');
        }
    }
}

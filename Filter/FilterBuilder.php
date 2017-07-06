<?php

namespace Ekyna\Component\Table\Filter;

use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\Util\Config;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class FilterBuilder
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FilterBuilder implements FilterBuilderInterface
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
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ResolvedFilterTypeInterface
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
     * @var array
     */
    private $options;


    /**
     * Constructor.
     *
     * @param string               $name
     * @param FormFactoryInterface $formFactory
     * @param array                $options
     */
    public function __construct($name, FormFactoryInterface $formFactory, array $options = [])
    {
        Config::validateName($name);

        $this->name = (string)$name;
        $this->options = $options;

        $this->setFormFactory($formFactory);
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
    public function getFormFactory()
    {
        return $this->formFactory;
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
    public function setType(ResolvedFilterTypeInterface $type)
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
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->preventIfLocked();

        $this->formFactory = $formFactory;

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
        $this->preventIfLocked();

        $this->propertyPath = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFilterConfig()
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
    public function getFilter()
    {
        $this->preventIfLocked();

        return new Filter($this->getFilterConfig());
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

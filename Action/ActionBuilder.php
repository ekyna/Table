<?php

namespace Ekyna\Component\Table\Action;

use Ekyna\Component\Table\Exception;
use Ekyna\Component\Table\Util\Config;

/**
 * Class ActionBuilder
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ActionBuilder implements ActionBuilderInterface
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
     * @var ResolvedActionTypeInterface
     */
    private $type;

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
    public function setType(ResolvedActionTypeInterface $type)
    {
        $this->preventIfLocked();

        $this->type = $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getActionConfig()
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
    public function getAction()
    {
        $this->preventIfLocked();

        return new Action($this->getActionConfig());
    }

    /**
     * Prevents method call if the config builder is locked.
     *
     * @throws Exception\BadMethodCallException
     */
    private function preventIfLocked()
    {
        if ($this->locked) {
            throw new Exception\BadMethodCallException('The action builder cannot be modified anymore.');
        }
    }
}

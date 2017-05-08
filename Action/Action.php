<?php

namespace Ekyna\Component\Table\Action;

use Ekyna\Component\Table\TableInterface;

/**
 * Class Action
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Action implements ActionInterface
{
    /**
     * @var ActionConfigInterface
     */
    private $config;

    /**
     * @var TableInterface
     */
    private $table;


    /**
     * Constructor.
     *
     * @param ActionConfigInterface $config
     */
    public function __construct(ActionConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function setTable(TableInterface $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->config->getName();
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $type = $this->config->getType();
        $options = $this->config->getOptions();

        return $type->execute($this, $options);
    }
}

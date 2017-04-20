<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Action;

use Ekyna\Component\Table\TableInterface;

/**
 * Class Action
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class Action implements ActionInterface
{
    private ActionConfigInterface $config;
    private ?TableInterface       $table = null;


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
    public function setTable(TableInterface $table = null): ActionInterface
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTable(): ?TableInterface
    {
        return $this->table;
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
    public function getLabel(): string
    {
        return $this->config->getLabel();
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): ActionConfigInterface
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

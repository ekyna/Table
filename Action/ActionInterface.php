<?php

namespace Ekyna\Component\Table\Action;

use Ekyna\Component\Table\TableInterface;

/**
 * Interface ActionInterface
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ActionInterface
{
    /**
     * Sets the table.
     *
     * @param TableInterface $table
     *
     * @return self
     */
    public function setTable(TableInterface $table);

    /**
     * Returns the table.
     *
     * @return TableInterface
     */
    public function getTable();

    /**
     * Returns the action's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the config.
     *
     * @return ActionConfigInterface
     */
    public function getConfig();

    /**
     * Executes the action.
     *
     * @return bool|object The response object or whether the action has been executed
     */
    public function execute();
}

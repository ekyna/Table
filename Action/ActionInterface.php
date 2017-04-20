<?php

declare(strict_types=1);

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
     * @param TableInterface|null $table
     *
     * @return ActionInterface
     */
    public function setTable(TableInterface $table = null): ActionInterface;

    /**
     * Returns the table.
     *
     * @return TableInterface|null
     */
    public function getTable(): ?TableInterface;

    /**
     * Returns the action's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the action's label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Returns the config.
     *
     * @return ActionConfigInterface
     */
    public function getConfig(): ActionConfigInterface;

    /**
     * Executes the action.
     *
     * @return bool|object The response object or whether the action has been executed
     */
    public function execute();
}

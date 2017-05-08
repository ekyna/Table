<?php

namespace Ekyna\Component\Table\Action;

/**
 * Interface ActionConfigInterface
 * @package Ekyna\Component\Table\Action
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ActionConfigInterface
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the action type used to construct the column.
     *
     * @return ResolvedActionTypeInterface
     */
    public function getType();

    /**
     * Returns all options passed during the construction of the action.
     *
     * @return array The passed options
     */
    public function getOptions();

    /**
     * Returns whether a specific option exists.
     *
     * @param string $name The option name,
     *
     * @return bool Whether the option exists
     */
    public function hasOption($name);

    /**
     * Returns the value of a specific option.
     *
     * @param string $name    The option name
     * @param mixed  $default The value returned if the option does not exist
     *
     * @return mixed The option value
     */
    public function getOption($name, $default = null);
}

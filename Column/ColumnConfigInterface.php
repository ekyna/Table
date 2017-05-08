<?php

namespace Ekyna\Component\Table\Column;

/**
 * Interface ColumnConfigInterface
 * @package Ekyna\Component\Table\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ColumnConfigInterface
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the column type used to construct the column.
     *
     * @return ResolvedColumnTypeInterface
     */
    public function getType();

    /**
     * Returns the column label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns the column position.
     *
     * @return int
     */
    public function getPosition();

    /**
     * Returns the column property path.
     *
     * @return string|null
     */
    public function getPropertyPath();

    /**
     * Returns whether the column is sortable.
     *
     * @return bool
     */
    public function isSortable();

    /**
     * Returns all options passed during the construction of the column.
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

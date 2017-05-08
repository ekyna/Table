<?php

namespace Ekyna\Component\Table\Filter;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * Interface FilterConfigInterface
 * @package Ekyna\Component\Table\Filter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface FilterConfigInterface
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the filter type used to construct the filter.
     *
     * @return ResolvedFilterTypeInterface
     */
    public function getType();

    /**
     * Returns the form factory.
     *
     * @return FormFactoryInterface
     */
    public function getFormFactory();

    /**
     * Returns the filter position.
     *
     * @return int
     */
    public function getPosition();

    /**
     * Returns the filter property path.
     *
     * @return string
     */
    public function getPropertyPath();

    /**
     * Returns all options passed during the construction of the filter.
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

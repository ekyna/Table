<?php

declare(strict_types=1);

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
    public function getName(): string;

    /**
     * Returns the filter type used to construct the filter.
     *
     * @return ResolvedFilterTypeInterface
     */
    public function getType(): ResolvedFilterTypeInterface;

    /**
     * Returns the filter label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Returns the form factory.
     *
     * @return FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface;

    /**
     * Returns the filter position.
     *
     * @return int
     */
    public function getPosition(): int;

    /**
     * Returns the filter property path.
     *
     * @return string
     */
    public function getPropertyPath(): string;

    /**
     * Returns all options passed during the construction of the filter.
     *
     * @return array The passed options
     */
    public function getOptions(): array;

    /**
     * Returns whether a specific option exists.
     *
     * @param string $name The option name,
     *
     * @return bool Whether the option exists
     */
    public function hasOption(string $name): bool;

    /**
     * Returns the value of a specific option.
     *
     * @param string $name    The option name
     * @param mixed  $default The value returned if the option does not exist
     *
     * @return mixed The option value
     */
    public function getOption(string $name, $default = null);
}

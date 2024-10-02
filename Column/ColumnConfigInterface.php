<?php

declare(strict_types=1);

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
    public function getName(): string;

    /**
     * Returns the column type used to construct the column.
     *
     * @return ResolvedColumnTypeInterface
     */
    public function getType(): ResolvedColumnTypeInterface;

    /**
     * Returns the column label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Returns the column position.
     *
     * @return int
     */
    public function getPosition(): int;

    /**
     * Returns the column property path.
     *
     * @return string|null
     */
    public function getPropertyPath(): ?string;

    /**
     * Returns whether the column is visible.
     *
     * @return bool
     */
    public function isVisible(): bool;

    /**
     * Returns whether the column is sortable.
     *
     * @return bool
     */
    public function isSortable(): bool;

    /**
     * Returns whether the column is exportable.
     *
     * @return bool
     */
    public function isExportable(): bool;

    /**
     * Returns all options passed during the construction of the column.
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

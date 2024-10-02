<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Column;

/**
 * Interface ColumnConfigBuilderInterface
 * @package Ekyna\Component\Table\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ColumnConfigBuilderInterface extends ColumnConfigInterface
{
    /**
     * Sets the column type.
     *
     * @param ResolvedColumnTypeInterface $type
     *
     * @return $this|ColumnConfigBuilderInterface
     */
    public function setType(ResolvedColumnTypeInterface $type): ColumnConfigBuilderInterface;

    /**
     * Sets the column label.
     *
     * @param string $label
     *
     * @return $this|ColumnConfigBuilderInterface
     */
    public function setLabel(string $label): ColumnConfigBuilderInterface;

    /**
     * Sets the column position.
     *
     * @param int $position
     *
     * @return $this|ColumnConfigBuilderInterface
     */
    public function setPosition(int $position): ColumnConfigBuilderInterface;

    /**
     * Sets the column property path.
     *
     * @param string|null $path
     *
     * @return $this|ColumnConfigBuilderInterface
     */
    public function setPropertyPath(?string $path): ColumnConfigBuilderInterface;

    /**
     * Sets whether the column is visible.
     *
     * @param bool $visible
     *
     * @return $this|ColumnConfigBuilderInterface
     */
    public function setVisible(bool $visible): ColumnConfigBuilderInterface;

    /**
     * Sets whether the column is sortable.
     *
     * @param bool $sortable
     *
     * @return $this|ColumnConfigBuilderInterface
     */
    public function setSortable(bool $sortable): ColumnConfigBuilderInterface;

    /**
     * Sets whether the column is exportable.
     *
     * @param bool $exportable
     *
     * @return $this|ColumnConfigBuilderInterface
     */
    public function setExportable(bool $exportable): ColumnConfigBuilderInterface;

    /**
     * Builds and returns the column configuration.
     *
     * @return ColumnConfigInterface
     */
    public function getColumnConfig(): ColumnConfigInterface;
}

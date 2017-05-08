<?php

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
     * @return $this
     */
    public function setType(ResolvedColumnTypeInterface $type);

    /**
     * Sets the column label.
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label);

    /**
     * Sets the column position.
     *
     * @param int $position
     *
     * @return $this
     */
    public function setPosition($position);

    /**
     * Sets the column property path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPropertyPath($path);

    /**
     * Sets whether the column is sortable.
     *
     * @param bool $sortable
     *
     * @return $this
     */
    public function setSortable($sortable);

    /**
     * Builds and returns the column configuration.
     *
     * @return ColumnConfigInterface
     */
    public function getColumnConfig();
}

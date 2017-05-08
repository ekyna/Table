<?php

namespace Ekyna\Component\Table\Column;

/**
 * Interface ColumnBuilderInterface
 * @package Ekyna\Component\Table\Column
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface ColumnBuilderInterface extends ColumnConfigBuilderInterface
{
    /**
     * Returns the column.
     *
     * @return Column
     */
    public function getColumn();
}

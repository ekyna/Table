<?php

namespace Ekyna\Component\Table\Source;

use Ekyna\Component\Table\Context\ContextInterface;

/**
 * Interface AdapterInterface
 * @package Ekyna\Component\Table\Adapter
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface AdapterInterface
{
    /**
     * Returns the grid (pager + rows).
     *
     * @param ContextInterface $context
     *
     * @return Grid
     */
    public function getGrid(ContextInterface $context);

    /**
     * Get selection.
     *
     * @param ContextInterface $context
     *
     * @return Row[]
     */
    public function getSelection(ContextInterface $context);
}

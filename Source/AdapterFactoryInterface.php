<?php

namespace Ekyna\Component\Table\Source;

use Ekyna\Component\Table\TableInterface;

/**
 * Interface AdapterFactoryInterface
 * @package Ekyna\Component\Table\Source
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface AdapterFactoryInterface
{
    /**
     * Creates an adapter for the given source.
     *
     * @param TableInterface $table
     *
     * @return AdapterInterface
     */
    public function createAdapter(TableInterface $table);
}

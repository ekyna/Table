<?php

namespace Ekyna\Component\Table\Context\Session;

use Ekyna\Component\Table\TableInterface;

/**
 * Interface StorageInterface
 * @package Ekyna\Component\Table\Context\Session
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface StorageInterface
{
    /**
     * Loads the context for the given table.
     *
     * @param TableInterface $table
     */
    public function load(TableInterface $table);

    /**
     * Saves the context for the given table.
     *
     * @param TableInterface $table
     */
    public function save(TableInterface $table);

    /**
     * Clears the context for the given table.
     *
     * @param TableInterface $table
     */
    public function clear(TableInterface $table);
}

<?php

declare(strict_types=1);

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
    public function load(TableInterface $table): void;

    /**
     * Saves the context for the given table.
     *
     * @param TableInterface $table
     */
    public function save(TableInterface $table): void;

    /**
     * Clears the context for the given table.
     *
     * @param TableInterface $table
     */
    public function clear(TableInterface $table): void;
}

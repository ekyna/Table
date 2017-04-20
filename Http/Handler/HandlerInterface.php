<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\TableInterface;

/**
 * Interface HandlerInterface
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface HandlerInterface
{
    /**
     * Handle the table.
     *
     * @param TableInterface $table
     * @param object|null    $request
     *
     * @return null|object The response object or null
     */
    public function execute(TableInterface $table, object $request = null): ?object;
}

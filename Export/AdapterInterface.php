<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Export;

use Ekyna\Component\Table\TableInterface;

/**
 * Interface AdapterInterface
 * @package Ekyna\Component\Table\Export
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface AdapterInterface
{
    /**
     * Export the given table using the given format.
     */
    public function export(TableInterface $table, string $format): ?string;

    /**
     * Returns the mime type for the given format.
     */
    public function getMimeType(string $format): string;

    /**
     * Returns whether the given format is supported.
     */
    public function supports(string $format): bool;
}

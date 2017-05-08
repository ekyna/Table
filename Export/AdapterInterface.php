<?php

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
     *
     * @param TableInterface $table
     * @param string         $format
     *
     * @return string
     */
    public function export(TableInterface $table, $format);

    /**
     * Returns the mime type for the given format.
     *
     * @param string $format
     *
     * @return string
     */
    public function getMimeType($format);

    /**
     * Returns whether the given format is supported.
     *
     * @param string $format
     *
     * @return bool
     */
    public function supports($format);
}

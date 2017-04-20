<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\Export\AdapterInterface;
use Ekyna\Component\Table\TableInterface;

use function sprintf;

/**
 * Class ExportHandler
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class ExportHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function execute(TableInterface $table, object $request = null): ?object
    {
        // Abort if table export is not enabled
        if (!$table->getConfig()->isExportable()) {
            return null;
        }

        $parameters = $table->getParametersHelper();

        // If export button is clicked
        if ($parameters->isExportClicked()) {
            // Get selected format
            $format = $parameters->getFormatValue();

            if (null === $adapter = $this->getAdapter($table, $format)) {
                return null;
            }

            // Body
            if (empty($body = $adapter->export($table, $format))) {
                return null;
            }

            // Response
            return $table->getConfig()->getHttpAdapter()->createResponse($body, 200, [
                'Content-Type'        => $adapter->getMimeType($format),
                'Content-Disposition' => sprintf('attachment; filename="export.%s"', $format),
            ]);
        }

        return null;
    }

    /**
     * Returns the adapter supporting the given format.
     *
     * @param TableInterface $table
     * @param string         $format
     *
     * @return AdapterInterface|null
     */
    private function getAdapter(TableInterface $table, string $format): ?AdapterInterface
    {
        $adapters = $table->getConfig()->getExportAdapters();

        foreach ($adapters as $adapter) {
            if ($adapter->supports($format)) {
                return $adapter;
            }
        }

        return null;
    }
}

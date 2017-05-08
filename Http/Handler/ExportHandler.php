<?php

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\TableInterface;

/**
 * Class ExportHandler
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ExportHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function execute(TableInterface $table, $request)
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
            try {
                $body = $adapter->export($table, $format);
            } catch (\Exception $e) {
                @trigger_error($e->getMessage());

                return null;
            }

            // Response
            $response = $table->getConfig()->getHttpAdapter()->createResponse($body, 200, [
                'Content-Type'        => $adapter->getMimeType($format),
                'Content-Disposition' => sprintf('attachment; filename="export.%s"', $format),
            ]);

            return $response;
        }

        return null;
    }

    /**
     * Returns the adapter supporting the given format.
     *
     * @param TableInterface $table
     * @param string         $format
     *
     * @return \Ekyna\Component\Table\Export\AdapterInterface|null
     */
    private function getAdapter(TableInterface $table, $format)
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

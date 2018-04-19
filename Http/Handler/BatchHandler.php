<?php

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\TableInterface;

/**
 * Class BatchHandler
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class BatchHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function execute(TableInterface $table, $request)
    {
        // Abort if table's batch actions are not enabled
        if (!$table->getConfig()->isBatchable()) {
            return null;
        }

        $parameters = $table->getParametersHelper();

        // If batch button is clicked
        if ($parameters->isBatchClicked()) {
            // Get selected action
            $name = $parameters->getActionValue();

            // If action exists
            if (!empty($name) && $table->hasAction($name)) {
                $result = $table->getAction($name)->execute();
                if (is_object($result)) {
                    return $result;
                }
            }

            return $table->getConfig()->getHttpAdapter()->redirect($table);
        }

        return null;
    }
}

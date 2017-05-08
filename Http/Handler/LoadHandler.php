<?php

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\TableInterface;

/**
 * Class LoadHandler
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class LoadHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function execute(TableInterface $table, $request)
    {
        $context = $table->getContext();
        $parameters = $table->getParametersHelper();

        if ($parameters->isConfigClicked()) {
            // Columns visibility
            if (!empty($names = $parameters->getColumnsValue())) {
                $context->setVisibleColumns($names);
            }

            // Max per page
            $choices = $table->getConfig()->getPerPageChoices();
            $maxPerPage = intval($parameters->getMaxPerPageValue());
            if (0 >= $maxPerPage || !in_array($maxPerPage, $choices)) {
                $maxPerPage = reset($choices);
            }
            $context->setMaxPerPage($maxPerPage);
        }

        // Current page
        $context->setCurrentPage($parameters->getPageValue() ?: 1);

        // Selected identifiers
        $context->setSelectedIdentifiers($parameters->getIdentifiersValue());
        $context->setAll($parameters->getAllValue());
    }
}

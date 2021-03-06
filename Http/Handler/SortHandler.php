<?php

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\TableInterface;

/**
 * Class SortHandler
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SortHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function execute(TableInterface $table, $request)
    {
        // Abort if table is not sortable
        if (!$table->getConfig()->isSortable()) {
            return null;
        }

        if (empty($sort = $table->getParametersHelper()->getSortValue())) {
            return null;
        }

        if (!isset($sort['by'], $sort['dir']) || !$table->hasColumn($sort['by'])) {
            return null;
        }

        $column = $table->getColumn($sort['by']);

        // Abort if column is not sortable
        if (!$column->getConfig()->isSortable()) {
            return null;
        }

        $sort = new ActiveSort($sort['by'], $sort['dir']);

        $table->getContext()->setActiveSort($sort);
    }
}

<?php

declare(strict_types=1);

namespace Ekyna\Component\Table\Http\Handler;

use Ekyna\Component\Table\Context\ActiveSort;
use Ekyna\Component\Table\TableInterface;
use Ekyna\Component\Table\Util\ColumnSort;

/**
 * Class SortHandler
 * @package Ekyna\Component\Table\Http\Handler
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class SortHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function execute(TableInterface $table, object $request = null): ?object
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

        if (ColumnSort::NONE === $sort['dir']) {
            $sort = null;
        } else {
            $sort = new ActiveSort($sort['by'], $sort['dir']);
        }

        $table->getContext()->setActiveSort($sort);

        return null;
    }
}
